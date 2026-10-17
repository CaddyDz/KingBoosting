<?php

declare(strict_types=1);

use App\Enums\{OrderStatus, Role};
use Illuminate\Support\Facades\DB;
use App\Models\{Message, Order, User};

it('builds an order in every status', function (string $state, OrderStatus $status, bool $hasBooster): void {
	$order = Order::factory()->{$state}()->create();

	expect($order->status)->toBe($status)
		->and($order->booster_id !== null)->toBe($hasBooster)
		->and($order->customer->role)->toBe(Role::Customer);

	if ($hasBooster) {
		expect($order->booster->role)->toBe(Role::Booster);
	}
})->with([
	'awaiting payment' => ['awaitingPayment', OrderStatus::AwaitingPayment, false],
	'pending' => ['pending', OrderStatus::Pending, false],
	'in progress' => ['inProgress', OrderStatus::InProgress, true],
	'paused' => ['paused', OrderStatus::Paused, true],
	'completed' => ['completed', OrderStatus::Completed, true],
	'paid out' => ['paidOut', OrderStatus::PaidOut, true],
]);

it('gives an order to a chosen booster', function (): void {
	$booster = User::factory()->booster()->create();

	expect(Order::factory()->completed($booster)->create()->booster->is($booster))->toBeTrue();
});

it('encrypts the account credentials at rest and hides them from serialisation', function (): void {
	$order = Order::factory()->create(['account_password' => 'hunter2']);

	expect(DB::table('orders')->value('account_password'))->not->toContain('hunter2')
		->and($order->fresh()->account_password)->toBe('hunter2')
		->and($order->toArray())->not->toHaveKeys(['account_login', 'account_password']);
});

it('rounds the booster share half up to the cent', function (int $price, int $pct, int $share): void {
	expect(Order::factory()->make(['price_cents' => $price, 'booster_share_pct' => $pct])->boosterShareCents())->toBe($share);
})->with([
	[13425, 50, 6713],
	[2000, 50, 1000],
	[999, 35, 350],
]);

it('treats a message without a sender as a system message', function (): void {
	$order = Order::factory()->inProgress()->create();

	expect(Message::factory()->for($order)->create()->sender->is($order->customer))->toBeTrue()
		->and(Message::factory()->for($order)->fromBooster()->create()->sender->is($order->booster))->toBeTrue()
		->and(Message::factory()->for($order)->system()->create()->isSystem())->toBeTrue();
});

it('makes new users customers', function (): void {
	expect(User::query()->create(['username' => 'newcomer', 'email' => 'new@kingboosting.test', 'password' => 'secret-password'])->fresh()->role)
		->toBe(Role::Customer);
});
