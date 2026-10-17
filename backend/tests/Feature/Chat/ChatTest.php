<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use App\Models\{Message, Order, User};
use App\Events\{MessageSent, MessagesRead};
use App\Actions\Orders\{ClaimOrder, PauseOrder};

/**
 * @return array{0: Order, 1: array<string, User>}
 */
function chatParties(string $state = 'inProgress'): array
{
	$order = Order::factory()->{$state}()->create();

	return [$order, [
		'admin' => User::factory()->admin()->create(),
		'moderator' => User::factory()->moderator()->create(),
		'holder' => $order->booster ?? User::factory()->booster()->create(),
		'booster' => User::factory()->booster()->create(),
		'owner' => $order->customer,
		'customer' => User::factory()->customer()->create(),
	]];
}

it('authorises the order channel for every role', function (string $state, array $allowed): void {
	// The null broadcaster used in tests authorises everything, so check against Reverb's
	config([
		'broadcasting.default' => 'reverb',
		'broadcasting.connections.reverb.key' => 'test-key',
		'broadcasting.connections.reverb.secret' => 'test-secret',
		'broadcasting.connections.reverb.app_id' => 'test',
	]);
	// Channels are registered on the broadcaster that was the default at boot, so register them on Reverb's
	require base_path('routes/channels.php');
	[$order, $users] = chatParties($state);

	foreach ($users as $actor => $user) {
		$response = $this->actingAs($user)->postJson('/broadcasting/auth', [
			'socket_id' => '1234.5678',
			'channel_name' => "private-orders.{$order->id}",
		]);

		in_array($actor, $allowed, true)
			? $response->assertOk()->assertJsonStructure(['auth'])
			: $response->assertForbidden();
	}
})->with([
	'in progress' => ['inProgress', ['admin', 'moderator', 'holder', 'owner']],
	'completed' => ['completed', ['admin', 'moderator', 'holder', 'owner']],
	'pending' => ['pending', ['admin', 'moderator', 'owner']],
	'awaiting payment' => ['awaitingPayment', []],
]);

it('posts a message and pushes it to the others on the order', function (): void {
	Event::fake([MessageSent::class]);
	[$order] = chatParties();

	$this->actingAs($order->customer)
		->postJson("/api/orders/{$order->id}/messages", ['body' => '  Can you play tonight?  '])
		->assertCreated()
		->assertJsonPath('data.body', 'Can you play tonight?')
		->assertJsonPath('data.sender', ['id' => $order->customer_id, 'name' => $order->customer->username, 'role' => 'customer']);

	Event::assertDispatched(MessageSent::class, fn (MessageSent $event): bool => $event->broadcastOn()->name === "private-orders.{$order->id}"
		&& $event->broadcastWith()['body'] === 'Can you play tonight?');
});

it('shows staff as Support', function (): void {
	[$order, $users] = chatParties();

	$this->actingAs($users['moderator'])
		->postJson("/api/orders/{$order->id}/messages", ['body' => 'Any problem with this order?'])
		->assertCreated()
		->assertJsonPath('data.sender.name', 'Support')
		->assertJsonPath('data.sender.role', 'support');
});

it('refuses the chat to anyone outside the order', function (string $actor): void {
	[$order, $users] = chatParties();

	$this->actingAs($users[$actor])->postJson("/api/orders/{$order->id}/messages", ['body' => 'hello'])->assertForbidden();
	$this->actingAs($users[$actor])->getJson("/api/orders/{$order->id}/messages")->assertForbidden();
})->with(['booster', 'customer']);

it('validates the message', function (): void {
	[$order] = chatParties();

	$this->actingAs($order->customer)
		->postJson("/api/orders/{$order->id}/messages", ['body' => str_repeat('a', 2001)])
		->assertUnprocessable()
		->assertJsonValidationErrors('body');
});

it('posts a system message for each order action', function (): void {
	$order = Order::factory()->pending()->create();
	$booster = User::factory()->booster()->create(['username' => 'nightowl']);

	app(ClaimOrder::class)->handle($booster, $order);
	app(PauseOrder::class)->handle($order->customer, $order);

	expect($order->messages()->orderBy('id')->get()->map(fn (Message $message): array => [$message->isSystem(), $message->body])->all())
		->toBe([
			[true, 'nightowl claimed the order.'],
			[true, 'The customer paused the order.'],
		]);
});

it('pages through the history, newest page first', function (): void {
	[$order] = chatParties();
	$messages = Message::factory()->count(35)->for($order)->create();

	$first = $this->actingAs($order->customer)->getJson("/api/orders/{$order->id}/messages")->assertOk();
	expect($first->json('data'))->toHaveCount(30)
		->and($first->json('data.0.id'))->toBe($messages[5]->id)
		->and($first->json('data.29.id'))->toBe($messages[34]->id)
		->and($first->json('meta'))->toBe(['has_more' => true, 'before' => $messages[5]->id]);

	$second = $this->getJson("/api/orders/{$order->id}/messages?before={$messages[5]->id}")->assertOk();
	expect(collect($second->json('data'))->pluck('id')->all())->toBe($messages->take(5)->pluck('id')->all())
		->and($second->json('meta.has_more'))->toBeFalse();
});

it('marks what the others sent as read', function (): void {
	Event::fake([MessagesRead::class]);
	[$order, $users] = chatParties();
	$fromBooster = Message::factory()->for($order)->fromBooster()->create();
	$system = Message::factory()->for($order)->system()->create();
	$own = Message::factory()->for($order)->create();

	$this->actingAs($order->customer)->postJson("/api/orders/{$order->id}/messages/read")->assertNoContent();

	expect($fromBooster->fresh()->read_at)->not->toBeNull()
		->and($system->fresh()->read_at)->not->toBeNull()
		->and($own->fresh()->read_at)->toBeNull();
	Event::assertDispatched(MessagesRead::class, fn (MessagesRead $event): bool => $event->upToId === $system->id);
});

it('doesn’t mark messages read when staff look', function (): void {
	[$order, $users] = chatParties();
	$message = Message::factory()->for($order)->create();

	$this->actingAs($users['admin'])->postJson("/api/orders/{$order->id}/messages/read")->assertNoContent();

	expect($message->fresh()->read_at)->toBeNull();
});
