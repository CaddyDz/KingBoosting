<?php

declare(strict_types=1);

use App\Exceptions\OrderConflict;
use App\Events\OrderEventRecorded;
use Illuminate\Support\Facades\Event;
use App\Models\{Order, OrderEvent, User};
use App\Enums\{OrderEventType, OrderStatus};
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use App\Actions\Orders\{AssignOrder, ClaimOrder, CompleteOrder, MarkOrderPaidOut, PauseOrder, ReleaseOrder, ResumeOrder, UpdateOrderCredentials};

function lastEvent(Order $order): OrderEvent
{
	return $order->events()->latest('id')->firstOrFail();
}

it('lets a booster claim a pending order', function (): void {
	Event::fake([OrderEventRecorded::class]);
	$booster = User::factory()->booster()->create();
	$order = Order::factory()->pending()->create();

	$claimed = app(ClaimOrder::class)->handle($booster, $order);

	expect($claimed->status)->toBe(OrderStatus::InProgress)
		->and($claimed->booster_id)->toBe($booster->id)
		->and($claimed->claimed_at)->not->toBeNull();
	expect(lastEvent($order))
		->type->toBe(OrderEventType::Claimed)
		->actor_id->toBe($booster->id)
		->from_status->toBe(OrderStatus::Pending)
		->to_status->toBe(OrderStatus::InProgress);
	Event::assertDispatched(OrderEventRecorded::class);
});

it('fails the second of two claims on the same order', function (): void {
	$first = User::factory()->booster()->create();
	$second = User::factory()->booster()->create();
	$order = Order::factory()->pending()->create();
	// Both boosters loaded the jobs board while the order was still pending
	$staleCopy = Order::query()->findOrFail($order->id);

	app(ClaimOrder::class)->handle($first, $order);

	expect(fn () => app(ClaimOrder::class)->handle($second, $staleCopy))
		->toThrow(OrderConflict::class, 'This order has already been claimed.');
	expect($order->fresh()->booster_id)->toBe($first->id)
		->and($order->events()->where('type', OrderEventType::Claimed)->count())->toBe(1);
});

it('answers 409 to a claim on an order that is no longer pending', function (): void {
	$order = Order::factory()->inProgress()->create();

	try {
		app(ClaimOrder::class)->handle(User::factory()->booster()->create(), $order);
		$this->fail('The claim should have been refused.');
	} catch (AuthorizationException $exception) {
		expect($exception->status())->toBe(409);
	}
});

it('lets staff assign a pending order to a booster', function (): void {
	$booster = User::factory()->booster()->create();
	$order = Order::factory()->pending()->create();

	$assigned = app(AssignOrder::class)->handle(User::factory()->moderator()->create(), $order, $booster);

	expect($assigned)
		->status->toBe(OrderStatus::InProgress)
		->booster_id->toBe($booster->id);
	expect(lastEvent($order))
		->type->toBe(OrderEventType::Assigned)
		->meta->toBe(['booster_id' => $booster->id]);
});

it('only assigns orders to boosters', function (): void {
	$order = Order::factory()->pending()->create();

	expect(fn () => app(AssignOrder::class)->handle(User::factory()->admin()->create(), $order, User::factory()->customer()->create()))
		->toThrow(ValidationException::class);
	expect($order->fresh()->status)->toBe(OrderStatus::Pending);
});

it('pauses and resumes an order', function (): void {
	$order = Order::factory()->inProgress()->create();

	app(PauseOrder::class)->handle($order->customer, $order);
	expect($order->fresh()->status)->toBe(OrderStatus::Paused)
		->and(lastEvent($order)->type)->toBe(OrderEventType::Paused);

	app(ResumeOrder::class)->handle($order->booster, $order);
	expect($order->fresh()->status)->toBe(OrderStatus::InProgress)
		->and(lastEvent($order)->type)->toBe(OrderEventType::Resumed);
});

it('releases an order back to the jobs board', function (): void {
	$order = Order::factory()->paused()->create();
	$booster = $order->booster;

	$released = app(ReleaseOrder::class)->handle($booster, $order);

	expect($released)
		->status->toBe(OrderStatus::Pending)
		->booster_id->toBeNull()
		->claimed_at->toBeNull();
	expect(lastEvent($order))
		->type->toBe(OrderEventType::Released)
		->from_status->toBe(OrderStatus::Paused)
		->meta->toBe(['booster_id' => $booster->id]);
});

it('lets the booster complete their order', function (): void {
	$order = Order::factory()->inProgress()->create();

	$completed = app(CompleteOrder::class)->handle($order->booster, $order);

	expect($completed->status)->toBe(OrderStatus::Completed)
		->and($completed->completed_at)->not->toBeNull()
		->and(lastEvent($order)->type)->toBe(OrderEventType::Completed);
});

it('marks a completed order paid out with the booster share', function (): void {
	$order = Order::factory()->completed()->create(['price_cents' => 13425, 'booster_share_pct' => 50]);

	$paidOut = app(MarkOrderPaidOut::class)->handle(User::factory()->admin()->create(), $order);

	expect($paidOut->status)->toBe(OrderStatus::PaidOut)
		->and($paidOut->paid_out_at)->not->toBeNull()
		->and(lastEvent($order)->meta)->toBe(['booster_share_cents' => 6713]);
});

it('updates the credentials without recording them', function (): void {
	$order = Order::factory()->inProgress()->create();

	app(UpdateOrderCredentials::class)->handle($order->customer, $order, 'new-login', 'new-password');

	expect($order->fresh())
		->account_login->toBe('new-login')
		->account_password->toBe('new-password')
		->status->toBe(OrderStatus::InProgress);
	expect(lastEvent($order))
		->type->toBe(OrderEventType::CredentialsUpdated)
		->meta->toBeNull();
});

it('changes nothing when the policy refuses', function (): void {
	$order = Order::factory()->inProgress()->create();

	expect(fn () => app(CompleteOrder::class)->handle($order->customer, $order))
		->toThrow(AuthorizationException::class);
	expect($order->fresh()->status)->toBe(OrderStatus::InProgress)
		->and($order->events()->count())->toBe(0);
});

it('checks the policy against the current row, not the caller’s copy', function (): void {
	$order = Order::factory()->inProgress()->create();
	$staleCopy = Order::query()->findOrFail($order->id);
	app(PauseOrder::class)->handle($order->customer, $order);

	// The stale copy still says in_progress, but the order is paused now
	expect(fn () => app(CompleteOrder::class)->handle($order->booster, $staleCopy))
		->toThrow(AuthorizationException::class, 'Only an order in progress can be completed.');
});
