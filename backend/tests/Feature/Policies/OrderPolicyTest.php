<?php

declare(strict_types=1);

use App\Enums\OrderStatus;
use App\Models\{Order, User};
use Illuminate\Support\Facades\Gate;

/*
 * Every ability × actor × status, written out by hand from the rules in docs/order-rules.md.
 * Actors: admin, moderator, the booster set on the order ("holder"), another booster,
 * the customer who placed it ("owner") and another customer.
 */

/**
 * @return array<string, array<string, list<string>>> ability => actor => statuses where it's allowed
 */
function orderPolicyRules(): array
{
	[$ap, $p, $ip, $pa, $c, $po] = ['awaiting_payment', 'pending', 'in_progress', 'paused', 'completed', 'paid_out'];
	$all = [$ap, $p, $ip, $pa, $c, $po];
	$paid = [$p, $ip, $pa, $c, $po];
	$claimed = [$ip, $pa, $c, $po];

	return [
		'view' => ['admin' => $all, 'moderator' => $all, 'holder' => $claimed, 'booster' => [$p], 'owner' => $all, 'customer' => []],
		'viewCredentials' => ['admin' => $all, 'moderator' => [], 'holder' => [$ip, $pa], 'booster' => [], 'owner' => $all, 'customer' => []],
		'viewPrice' => ['admin' => $all, 'moderator' => [], 'holder' => [], 'booster' => [], 'owner' => $all, 'customer' => []],
		'viewBoosterShare' => ['admin' => $all, 'moderator' => [], 'holder' => $claimed, 'booster' => [$p], 'owner' => [], 'customer' => []],
		'claim' => ['admin' => [], 'moderator' => [], 'holder' => [], 'booster' => [$p], 'owner' => [], 'customer' => []],
		'assign' => ['admin' => [$p], 'moderator' => [$p], 'holder' => [], 'booster' => [], 'owner' => [], 'customer' => []],
		'pause' => ['admin' => [$ip], 'moderator' => [$ip], 'holder' => [$ip], 'booster' => [], 'owner' => [$ip], 'customer' => []],
		'resume' => ['admin' => [$pa], 'moderator' => [$pa], 'holder' => [$pa], 'booster' => [], 'owner' => [$pa], 'customer' => []],
		'release' => ['admin' => [$ip, $pa], 'moderator' => [$ip, $pa], 'holder' => [$ip, $pa], 'booster' => [], 'owner' => [], 'customer' => []],
		'complete' => ['admin' => [], 'moderator' => [], 'holder' => [$ip], 'booster' => [], 'owner' => [], 'customer' => []],
		'markPaidOut' => ['admin' => [$c], 'moderator' => [], 'holder' => [], 'booster' => [], 'owner' => [], 'customer' => []],
		'updateCredentials' => ['admin' => [], 'moderator' => [], 'holder' => [], 'booster' => [], 'owner' => [$ap, $p, $ip, $pa], 'customer' => []],
		'pay' => ['admin' => [], 'moderator' => [], 'holder' => [], 'booster' => [], 'owner' => [$ap], 'customer' => []],
		'chat' => ['admin' => $paid, 'moderator' => $paid, 'holder' => $claimed, 'booster' => [], 'owner' => $paid, 'customer' => []],
	];
}

dataset('policy cases', function (): Generator {
	foreach (orderPolicyRules() as $ability => $actors) {
		foreach ($actors as $actor => $allowed) {
			foreach (OrderStatus::cases() as $case) {
				$status = $case->value;
				// A pending order has no booster yet, so it has no holder to ask about
				if ($actor === 'holder' && $status === 'pending') {
					continue;
				}
				yield "{$ability} · {$actor} · {$status}" => [$ability, $actor, $status, in_array($status, $allowed, true)];
			}
		}
	}
});

it('enforces the order rules', function (string $ability, string $actor, string $status, bool $expected): void {
	$holder = User::factory()->booster()->create();
	$owner = User::factory()->customer()->create();
	$order = Order::factory()->for($owner, 'customer')->create([
		'status' => OrderStatus::from($status),
		// The awaiting-payment order has the booster the customer picked at checkout
		'booster_id' => $status === 'pending' ? null : $holder->id,
	]);

	$user = match ($actor) {
		'admin' => User::factory()->admin()->create(),
		'moderator' => User::factory()->moderator()->create(),
		'holder' => $holder,
		'booster' => User::factory()->booster()->create(),
		'owner' => $owner,
		'customer' => User::factory()->customer()->create(),
	};

	expect(Gate::forUser($user)->allows($ability, $order))->toBe($expected);
})->with('policy cases');

it('answers 409 when the actor could act but the status no longer allows it', function (): void {
	$order = Order::factory()->inProgress()->create();

	$response = Gate::forUser(User::factory()->booster()->create())->inspect('claim', $order);

	expect($response->allowed())->toBeFalse()
		->and($response->status())->toBe(409)
		->and($response->message())->toBe('This order has already been claimed.');
});

it('answers 403 when the actor may never act on the order', function (): void {
	$order = Order::factory()->inProgress()->create();

	$response = Gate::forUser(User::factory()->customer()->create())->inspect('complete', $order);

	expect($response->allowed())->toBeFalse()
		->and($response->status())->toBeNull();
});

it('only lets each status move along the lifecycle', function (OrderStatus $from, array $to): void {
	foreach (OrderStatus::cases() as $status) {
		expect($from->canTransitionTo($status))->toBe(in_array($status, $to, true));
	}
})->with([
	'awaiting payment' => [OrderStatus::AwaitingPayment, [OrderStatus::Pending, OrderStatus::InProgress]],
	'pending' => [OrderStatus::Pending, [OrderStatus::InProgress]],
	'in progress' => [OrderStatus::InProgress, [OrderStatus::Paused, OrderStatus::Pending, OrderStatus::Completed]],
	'paused' => [OrderStatus::Paused, [OrderStatus::InProgress, OrderStatus::Pending]],
	'completed' => [OrderStatus::Completed, [OrderStatus::PaidOut]],
	'paid out' => [OrderStatus::PaidOut, []],
]);
