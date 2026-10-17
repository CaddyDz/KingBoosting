<?php

declare(strict_types=1);

use App\Models\{Order, User};
use App\Actions\Orders\ClaimOrder;

/**
 * @return list<int>
 */
function listedIds(\Illuminate\Testing\TestResponse $response): array
{
	return collect($response->json('data'))->pluck('id')->sort()->values()->all();
}

it('lists only the customer’s own orders', function (): void {
	$customer = User::factory()->customer()->create();
	$own = Order::factory()->count(2)->for($customer, 'customer')->create();
	$unpaid = Order::factory()->awaitingPayment()->for($customer, 'customer')->create();
	Order::factory()->inProgress()->create();
	Order::factory()->pending()->create();

	$response = $this->actingAs($customer)->getJson('/api/orders')->assertOk();

	expect(listedIds($response))->toBe($own->pluck('id')->push($unpaid->id)->sort()->values()->all());
});

it('refuses to show a customer someone else’s order', function (): void {
	$order = Order::factory()->inProgress()->create();

	$this->actingAs(User::factory()->customer()->create())
		->getJson("/api/orders/{$order->id}")
		->assertForbidden();
});

it('lists only the orders a booster holds, and filters narrow that list', function (): void {
	$booster = User::factory()->booster()->create();
	$playing = Order::factory()->inProgress($booster)->create();
	$done = Order::factory()->completed($booster)->create();
	Order::factory()->count(2)->pending()->create();
	Order::factory()->inProgress()->create();
	Order::factory()->completed()->create();

	$this->actingAs($booster);

	expect(listedIds($this->getJson('/api/orders')))->toBe([$playing->id, $done->id])
		->and(listedIds($this->getJson('/api/orders?status=completed')))->toBe([$done->id])
		->and(listedIds($this->getJson('/api/orders?status=pending')))->toBe([]);
});

it('refuses to show a booster another booster’s order', function (): void {
	$order = Order::factory()->inProgress()->create();

	$this->actingAs(User::factory()->booster()->create())
		->getJson("/api/orders/{$order->id}")
		->assertForbidden();
});

it('shows a booster the credentials only while they are playing the order', function (): void {
	$booster = User::factory()->booster()->create();
	$playing = Order::factory()->inProgress($booster)->create(['account_password' => 'hunter2']);
	$done = Order::factory()->completed($booster)->create();
	$pending = Order::factory()->pending()->create();

	$this->actingAs($booster);

	$this->getJson("/api/orders/{$playing->id}")->assertOk()->assertJsonPath('data.credentials.password', 'hunter2');
	$this->getJson("/api/orders/{$done->id}")->assertOk()->assertJsonMissingPath('data.credentials');
	$this->getJson("/api/orders/{$pending->id}")->assertOk()
		->assertJsonMissingPath('data.credentials')
		->assertJsonMissingPath('data.customer')
		->assertJsonPath('data.allowed_actions', ['claim']);
});

it('shows boosters their share but never the price', function (): void {
	$booster = User::factory()->booster()->create();
	$order = Order::factory()->inProgress($booster)->create(['price_cents' => 10000, 'booster_share_pct' => 50]);

	$this->actingAs($booster)->getJson("/api/orders/{$order->id}")
		->assertOk()
		->assertJsonPath('data.booster_share_cents', 5000)
		->assertJsonMissingPath('data.price_cents')
		->assertJsonMissingPath('data.quote');
});

it('shows moderators every order without prices or credentials', function (): void {
	$orders = Order::factory()->count(3)->inProgress()->create();

	$this->actingAs($moderator = User::factory()->moderator()->create());

	expect(listedIds($this->getJson('/api/orders')))->toBe($orders->pluck('id')->sort()->values()->all());
	$this->getJson("/api/orders/{$orders[0]->id}")
		->assertOk()
		->assertJsonMissingPath('data.price_cents')
		->assertJsonMissingPath('data.booster_share_cents')
		->assertJsonMissingPath('data.credentials');
});

it('shows admins the price and credentials on the order page only', function (): void {
	$order = Order::factory()->inProgress()->create(['price_cents' => 4200, 'account_password' => 'hunter2']);

	$this->actingAs(User::factory()->admin()->create());

	$this->getJson('/api/orders')->assertOk()
		->assertJsonPath('data.0.price_cents', 4200)
		->assertJsonMissingPath('data.0.credentials');
	$this->getJson("/api/orders/{$order->id}")->assertOk()
		->assertJsonPath('data.credentials.password', 'hunter2')
		->assertJsonPath('data.quote.total_cents', 4200);
});

it('tells the customer what they can do to their order', function (): void {
	$order = Order::factory()->inProgress()->create();

	$this->actingAs($order->customer)->getJson("/api/orders/{$order->id}")
		->assertOk()
		->assertJsonPath('data.allowed_actions', ['pause', 'update-credentials'])
		->assertJsonPath('data.can_chat', true)
		->assertJsonPath('data.price_cents', $order->price_cents);
});

it('shows the timeline with staff as Support', function (): void {
	$order = Order::factory()->pending()->create();
	app(\App\Actions\Orders\AssignOrder::class)->handle(User::factory()->admin()->create(), $order, $booster = User::factory()->booster()->create());

	$this->actingAs($order->customer)->getJson("/api/orders/{$order->id}")
		->assertOk()
		->assertJsonPath('data.events.0.type', 'assigned')
		->assertJsonPath('data.events.0.actor', 'Support')
		->assertJsonPath('data.booster.username', $booster->username);
});

it('lists the jobs board for boosters, without credentials or prices', function (): void {
	$jobs = Order::factory()->count(2)->pending()->create(['price_cents' => 2000]);
	Order::factory()->inProgress()->create();
	Order::factory()->awaitingPayment()->create();

	$response = $this->actingAs(User::factory()->booster()->create())->getJson('/api/jobs')->assertOk();

	expect(listedIds($response))->toBe($jobs->pluck('id')->sort()->values()->all());
	$response->assertJsonPath('data.0.booster_share_cents', 1000)
		->assertJsonMissingPath('data.0.price_cents')
		->assertJsonMissingPath('data.0.credentials');
});

it('keeps customers off the jobs board', function (): void {
	$this->actingAs(User::factory()->customer()->create())->getJson('/api/jobs')->assertForbidden();
});

it('drops a claimed order from the jobs board', function (): void {
	$order = Order::factory()->pending()->create();
	app(ClaimOrder::class)->handle(User::factory()->booster()->create(), $order);

	$response = $this->actingAs(User::factory()->booster()->create())->getJson('/api/jobs')->assertOk();

	expect(listedIds($response))->toBe([]);
});

it('searches and paginates for staff', function (): void {
	$customer = User::factory()->customer()->create(['username' => 'needle']);
	$match = Order::factory()->for($customer, 'customer')->create();
	Order::factory()->count(4)->create();

	$this->actingAs(User::factory()->admin()->create());

	expect(listedIds($this->getJson('/api/orders?search=needle')))->toBe([$match->id])
		->and(listedIds($this->getJson("/api/orders?search=%23{$match->id}")))->toBe([$match->id]);
	$this->getJson('/api/orders?per_page=2')->assertJsonCount(2, 'data')->assertJsonPath('meta.total', 5);
});

it('refuses guests', function (): void {
	$this->getJson('/api/orders')->assertUnauthorized();
	$this->getJson('/api/jobs')->assertUnauthorized();
});

it('shows the customer a paid-out order as completed, without the payout', function (): void {
	$order = Order::factory()->paidOut()->create();
	$order->recordEvent(\App\Enums\OrderEventType::PaidOut, User::factory()->admin()->create(), \App\Enums\OrderStatus::Completed, \App\Enums\OrderStatus::PaidOut);

	$this->actingAs($order->customer)->getJson("/api/orders/{$order->id}")
		->assertOk()
		->assertJsonPath('data.status', ['value' => 'paid_out', 'label' => 'Completed'])
		->assertJsonMissingPath('data.paid_out_at')
		->assertJsonCount(0, 'data.events');
});
