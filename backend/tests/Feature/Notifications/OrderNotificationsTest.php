<?php

declare(strict_types=1);

use App\Models\{Order, User};
use Illuminate\Support\Facades\Notification;
use App\Notifications\{AdminNotice, NewOrderAvailable, OrderAssigned, OrderStatusChanged, PaymentFailed};
use App\Actions\Orders\{AssignOrder, ClaimOrder, ConfirmPayment, PauseOrder, RecordPaymentFailure, ReleaseOrder};

beforeEach(function (): void {
	Notification::fake();
});

it('tells every booster about a new job and the customer about the payment', function (): void {
	$boosters = User::factory()->booster()->count(2)->create();
	$order = Order::factory()->awaitingPayment()->create();

	app(ConfirmPayment::class)->handle($order);

	Notification::assertSentTo($boosters, NewOrderAvailable::class);
	Notification::assertSentTo($order->customer, OrderStatusChanged::class, fn (OrderStatusChanged $notification): bool => $notification->summary === 'Payment received. The order is waiting for a booster.');
	Notification::assertNotSentTo($order->customer, NewOrderAvailable::class);
});

it('tells the booster the customer picked, and nobody else', function (): void {
	$picked = User::factory()->booster()->create();
	$other = User::factory()->booster()->create();
	$order = Order::factory()->awaitingPayment()->create(['booster_id' => $picked->id]);

	app(ConfirmPayment::class)->handle($order);

	Notification::assertSentTo($picked, OrderAssigned::class);
	Notification::assertNotSentTo($picked, OrderStatusChanged::class);
	Notification::assertNothingSentTo($other);
	Notification::assertSentTo($order->customer, OrderStatusChanged::class);
});

it('tells the booster and the customer when staff assign an order', function (): void {
	$order = Order::factory()->pending()->create();
	$booster = User::factory()->booster()->create();
	$admin = User::factory()->admin()->create();

	app(AssignOrder::class)->handle($admin, $order, $booster);

	Notification::assertSentTo($booster, OrderAssigned::class);
	Notification::assertSentTo($order->customer, OrderStatusChanged::class, fn (OrderStatusChanged $notification): bool => $notification->summary === "Support gave the order to {$booster->username}.");
	Notification::assertNothingSentTo($admin);
});

it('tells the customer, not the booster, about a claim', function (): void {
	$order = Order::factory()->pending()->create();
	$booster = User::factory()->booster()->create();

	app(ClaimOrder::class)->handle($booster, $order);

	Notification::assertSentTo($order->customer, OrderStatusChanged::class);
	Notification::assertNothingSentTo($booster);
});

it('tells the booster when the customer pauses', function (): void {
	$order = Order::factory()->inProgress()->create();

	app(PauseOrder::class)->handle($order->customer, $order);

	Notification::assertSentTo($order->booster, OrderStatusChanged::class, fn (OrderStatusChanged $notification): bool => $notification->summary === 'The customer paused the order.');
	Notification::assertNothingSentTo($order->customer);
});

it('tells the customer and the booster who lost the order when staff release it', function (): void {
	$order = Order::factory()->inProgress()->create();
	$booster = $order->booster;

	app(ReleaseOrder::class)->handle(User::factory()->moderator()->create(), $order);

	Notification::assertSentTo([$order->customer, $booster], OrderStatusChanged::class);
});

it('tells the customer when their payment fails', function (): void {
	$order = Order::factory()->awaitingPayment()->create();

	app(RecordPaymentFailure::class)->handle($order, 'Your card has insufficient funds.');

	Notification::assertSentTo($order->customer, PaymentFailed::class, fn (PaymentFailed $notification): bool => $notification->reason === 'Your card has insufficient funds.');
});

it('emails a link to the order in the dashboard', function (): void {
	$order = Order::factory()->inProgress()->create();

	$mail = (new OrderStatusChanged($order, 'The customer paused the order.'))->toMail($order->booster);

	expect($mail->subject)->toBe("Order #{$order->id}: In progress")
		->and($mail->actionUrl)->toBe(config('app.frontend_url')."/dashboard/orders/{$order->id}")
		->and($mail->introLines)->toBe(["{$order->title}: The customer paused the order."]);
});

it('lets an admin message a user', function (): void {
	$user = User::factory()->booster()->create();

	$this->actingAs(User::factory()->admin()->create())
		->postJson("/api/users/{$user->id}/notices", ['message' => 'Please update your payout email.'])
		->assertNoContent();

	Notification::assertSentTo($user, AdminNotice::class, fn (AdminNotice $notice): bool => $notice->message === 'Please update your payout email.');
});

it('keeps admin notices to admins', function (): void {
	$user = User::factory()->booster()->create();

	$this->actingAs(User::factory()->moderator()->create())
		->postJson("/api/users/{$user->id}/notices", ['message' => 'Hello'])
		->assertForbidden();

	Notification::assertNothingSent();
});

it('keeps the status the order had when it changed', function (): void {
	$order = Order::factory()->pending()->create();
	$notification = new OrderStatusChanged($order, 'Payment received. The order is waiting for a booster.');

	app(ClaimOrder::class)->handle(User::factory()->booster()->create(), $order);

	expect($notification->title())->toBe("Order #{$order->id}: Waiting for a booster");
});
