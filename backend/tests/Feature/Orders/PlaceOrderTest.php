<?php

declare(strict_types=1);

use App\Models\{Order, User};
use App\Payments\PaymentGateway;
use Tests\Fakes\FakePaymentGateway;
use App\Enums\{OrderEventType, OrderStatus};

beforeEach(function (): void {
	$this->app->instance(PaymentGateway::class, $this->payments = new FakePaymentGateway);
});

/**
 * A League division order from Bronze I to Gold III: 134.25 € in the legacy storefront.
 *
 * @return array<string, mixed>
 */
function divisionOrder(array $overrides = []): array
{
	return [
		'service' => 'division-boosting',
		'server' => 'euw',
		'account_name' => 'Faker',
		'account_login' => 'faker.riot',
		'account_password' => 'hunter2',
		'current' => ['tier' => 'bronze', 'division' => 'I'],
		'desired' => ['tier' => 'gold', 'division' => 'III'],
		'lp' => '21-40',
		'mmr' => '15-17',
		...$overrides,
	];
}

it('places an order priced on the server and opens a payment intent for it', function (): void {
	$customer = User::factory()->customer()->create();

	$response = $this->actingAs($customer)->postJson('/api/orders', divisionOrder())
		->assertCreated()
		->assertJsonPath('data.status.value', 'awaiting_payment')
		->assertJsonPath('data.title', 'Bronze I → Gold III')
		->assertJsonPath('data.price_cents', 13425)
		->assertJsonPath('data.credentials.password', 'hunter2');

	$order = Order::query()->sole();
	expect($response->json('client_secret'))->toBe("pi_fake_{$order->id}_secret_test")
		->and($order->customer_id)->toBe($customer->id)
		->and($order->payment_intent_id)->toBe("pi_fake_{$order->id}")
		->and($order->quote['total_cents'])->toBe(13425)
		->and($this->payments->intents)->toBe([$order->id => 13425])
		->and($order->events()->sole()->type)->toBe(OrderEventType::Created);
});

it('ignores any price the client sends', function (): void {
	$this->actingAs(User::factory()->customer()->create())
		->postJson('/api/orders', divisionOrder(['price' => 1, 'price_cents' => 100, 'total_cents' => 100, 'quote' => ['total_cents' => 100]]))
		->assertCreated()
		->assertJsonPath('data.price_cents', 13425);

	expect(Order::query()->sole()->price_cents)->toBe(13425)
		->and(array_values($this->payments->intents))->toBe([13425]);
});

it('prices the options the customer picks', function (): void {
	$this->actingAs(User::factory()->customer()->create())
		->postJson('/api/orders', divisionOrder(['options' => ['priority', 'appear-offline']]))
		->assertCreated()
		->assertJsonPath('data.price_cents', 16110)
		->assertJsonPath('data.options', ['Appear offline on chat', 'Priority order']);
});

it('keeps a booster picked at checkout on the unpaid order', function (): void {
	$booster = User::factory()->booster()->create();

	$this->actingAs(User::factory()->customer()->create())
		->postJson('/api/orders', divisionOrder(['booster_id' => $booster->id]))
		->assertCreated();

	expect(Order::query()->sole())
		->booster_id->toBe($booster->id)
		->status->toBe(OrderStatus::AwaitingPayment);
	// The booster can't see it until it's paid
	$this->actingAs($booster)->getJson('/api/orders')->assertJsonCount(0, 'data');
});

it('only accepts a visible booster', function (User $booster): void {
	$this->actingAs(User::factory()->customer()->create())
		->postJson('/api/orders', divisionOrder(['booster_id' => $booster->id]))
		->assertUnprocessable()
		->assertJsonValidationErrors('booster_id');
})->with([
	'hidden booster' => fn () => User::factory()->booster()->hidden()->create(),
	'admin' => fn () => User::factory()->admin()->create(),
	'customer' => fn () => User::factory()->customer()->create(),
]);

it('validates the order and its quote', function (array $overrides, string $field): void {
	$this->actingAs(User::factory()->customer()->create())
		->postJson('/api/orders', divisionOrder($overrides))
		->assertUnprocessable()
		->assertJsonValidationErrors($field);

	expect(Order::query()->exists())->toBeFalse();
})->with([
	'a Valorant server' => [['server' => 'ap'], 'server'],
	'no in-game name' => [['account_name' => ''], 'account_name'],
	'a login without a password' => [['account_password' => null], 'account_password'],
	'an unknown service' => [['service' => 'coaching'], 'service'],
	'a rank below the current one' => [['desired' => ['tier' => 'iron', 'division' => 'IV']], 'desired'],
	'another service’s option' => [['options' => ['net-wins']], 'options.0'],
]);

it('lets the customer add credentials later', function (): void {
	$this->actingAs(User::factory()->customer()->create())
		->postJson('/api/orders', divisionOrder(['account_login' => null, 'account_password' => null]))
		->assertCreated()
		->assertJsonPath('data.allowed_actions', ['update-credentials', 'pay']);
});

it('only lets customers place orders', function (User $user): void {
	$this->actingAs($user)->postJson('/api/orders', divisionOrder())->assertForbidden();
})->with([
	'booster' => fn () => User::factory()->booster()->create(),
	'moderator' => fn () => User::factory()->moderator()->create(),
	'admin' => fn () => User::factory()->admin()->create(),
]);

it('answers 503 and keeps nothing payable when Stripe is down', function (): void {
	$this->payments->failing = true;

	$this->actingAs(User::factory()->customer()->create())
		->postJson('/api/orders', divisionOrder())
		->assertServiceUnavailable();

	expect(Order::query()->sole())
		->status->toBe(OrderStatus::AwaitingPayment)
		->payment_intent_id->toBeNull();
});

it('refuses guests', function (): void {
	$this->postJson('/api/orders', divisionOrder())->assertUnauthorized();
});

it('gives the customer the client secret of an unpaid order, so checkout can take the payment again', function (): void {
	$customer = User::factory()->customer()->create();
	$order = Order::query()->findOrFail($this->actingAs($customer)->postJson('/api/orders', divisionOrder())->json('data.id'));

	$this->getJson("/api/orders/{$order->id}")->assertJsonPath('data.allowed_actions', ['update-credentials', 'pay']);
	$this->getJson("/api/orders/{$order->id}/payment")
		->assertOk()
		->assertExactJson(['data' => ['client_secret' => "pi_fake_{$order->id}_secret_test", 'amount_cents' => 13425]]);
});

it('keeps the payment of an order to its customer, until it is paid', function (): void {
	$unpaid = Order::factory()->awaitingPayment()->create(['payment_intent_id' => 'pi_fake_1']);
	$paid = Order::factory()->pending()->create(['payment_intent_id' => 'pi_fake_2']);

	$this->actingAs(User::factory()->customer()->create())->getJson("/api/orders/{$unpaid->id}/payment")->assertForbidden();
	$this->actingAs(User::factory()->admin()->create())->getJson("/api/orders/{$unpaid->id}/payment")->assertForbidden();
	$this->actingAs($paid->customer)->getJson("/api/orders/{$paid->id}/payment")
		->assertConflict()
		->assertJsonPath('message', 'This order is already paid.');
	$this->actingAs($paid->customer)->getJson("/api/orders/{$paid->id}")->assertJsonPath('data.allowed_actions', ['update-credentials']);
});
