<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use App\Models\{Order, User};
use App\Payments\PaymentGateway;
use Tests\Fakes\FakePaymentGateway;
use Illuminate\Testing\TestResponse;
use Illuminate\Support\Facades\Exceptions;
use App\Enums\{OrderEventType, OrderStatus};
use App\Payments\{PaymentAlreadyTaken, PaymentProviderUnavailable};

beforeEach(function (): void {
	config(['services.stripe.webhook_secret' => 'whsec_test_secret']);
	$this->app->instance(PaymentGateway::class, $this->payments = new FakePaymentGateway);
});

/**
 * A Stripe event from tests/Fixtures/stripe for this order's PaymentIntent, with a fresh event id.
 *
 * @param  array<string, mixed>  $intent  overrides for the PaymentIntent
 * @return array<string, mixed>
 */
function stripeEvent(string $type, Order $order, array $intent = []): array
{
	$event = json_decode((string) file_get_contents(dirname(__DIR__, 2)."/Fixtures/stripe/{$type}.json"), true);
	$event['id'] = 'evt_'.Str::random(24);
	$event['data']['object'] = [
		...$event['data']['object'],
		'id' => $order->payment_intent_id,
		'amount' => $order->price_cents,
		'metadata' => ['order_id' => (string) $order->id],
		...($type === 'payment_intent.succeeded' ? ['amount_received' => $order->price_cents] : []),
		...$intent,
	];

	return $event;
}

/**
 * POST the event the way Stripe does: `Stripe-Signature: t=<time>,v1=<HMAC-SHA256 of "<time>.<payload>">`.
 *
 * @param  array<string, mixed>  $event
 */
function sendStripeEvent(array $event, string $secret = 'whsec_test_secret', ?int $timestamp = null): TestResponse
{
	$payload = (string) json_encode($event);
	$timestamp ??= time();
	$signature = hash_hmac('sha256', "{$timestamp}.{$payload}", $secret);

	return test()->call('POST', '/stripe/webhook', server: [
		'HTTP_STRIPE_SIGNATURE' => "t={$timestamp},v1={$signature}",
		'CONTENT_TYPE' => 'application/json',
	], content: $payload);
}

it('moves a paid order to the jobs board', function (): void {
	$order = Order::factory()->awaitingPayment()->create(['price_cents' => 13425]);

	sendStripeEvent(stripeEvent('payment_intent.succeeded', $order))->assertOk();

	expect($order->fresh())
		->status->toBe(OrderStatus::Pending)
		->paid_at->not->toBeNull()
		->booster_id->toBeNull();
	expect($order->events()->sole())
		->type->toBe(OrderEventType::PaymentSucceeded)
		->actor_id->toBeNull()
		->to_status->toBe(OrderStatus::Pending);
});

it('gives a paid order straight to the booster the customer picked', function (): void {
	$booster = User::factory()->booster()->create();
	$order = Order::factory()->awaitingPayment()->create(['booster_id' => $booster->id]);

	sendStripeEvent(stripeEvent('payment_intent.succeeded', $order))->assertOk();

	expect($order->fresh())
		->status->toBe(OrderStatus::InProgress)
		->booster_id->toBe($booster->id)
		->claimed_at->not->toBeNull();
});

it('puts the order on the jobs board when the picked booster has hidden since', function (): void {
	$booster = User::factory()->booster()->create();
	$order = Order::factory()->awaitingPayment()->create(['booster_id' => $booster->id]);
	$booster->forceFill(['visible' => false])->save();

	sendStripeEvent(stripeEvent('payment_intent.succeeded', $order))->assertOk();

	expect($order->fresh())
		->status->toBe(OrderStatus::Pending)
		->booster_id->toBeNull();
});

it('handles each event once, however often Stripe sends it', function (): void {
	$order = Order::factory()->awaitingPayment()->create();
	$event = stripeEvent('payment_intent.succeeded', $order);

	sendStripeEvent($event)->assertOk()->assertSeeText('OK');
	sendStripeEvent($event)->assertOk()->assertSeeText('Already processed');

	expect($order->events()->count())->toBe(1);
});

it('ignores a second success for an order that was already paid', function (): void {
	$order = Order::factory()->awaitingPayment()->create();

	sendStripeEvent(stripeEvent('payment_intent.succeeded', $order))->assertOk();
	sendStripeEvent(stripeEvent('payment_intent.succeeded', $order))->assertOk();

	expect($order->events()->count())->toBe(1);
});

it('rejects a bad or stale signature', function (string $secret, int $age): void {
	$order = Order::factory()->awaitingPayment()->create();

	sendStripeEvent(stripeEvent('payment_intent.succeeded', $order), $secret, time() - $age)->assertBadRequest();

	expect($order->fresh()->status)->toBe(OrderStatus::AwaitingPayment);
})->with([
	'wrong secret' => ['whsec_somebody_else', 0],
	'replayed after 10 minutes' => ['whsec_test_secret', 600],
]);

it('refunds a payment that doesn’t match the order price and keeps the order unpaid', function (array $intent): void {
	$order = Order::factory()->awaitingPayment()->create(['price_cents' => 13425]);

	sendStripeEvent(stripeEvent('payment_intent.succeeded', $order, $intent))->assertOk()->assertSeeText('Amount mismatch, refunded');

	expect($order->fresh()->status)->toBe(OrderStatus::AwaitingPayment)
		->and($this->payments->refunded)->toBe([$order->payment_intent_id]);
	expect($order->events()->sole())
		->type->toBe(OrderEventType::PaymentFailed)
		->meta->toBe(['reason' => "The amount charged didn't match the order price, so we refunded it."]);
})->with([
	'amount' => [['amount_received' => 100]],
	'currency' => [['currency' => 'usd']],
]);

it('asks Stripe to send a mismatched payment again when the refund fails', function (): void {
	$order = Order::factory()->awaitingPayment()->create(['price_cents' => 13425]);
	$event = stripeEvent('payment_intent.succeeded', $order, ['amount_received' => 100]);
	$this->payments->failing = true;

	sendStripeEvent($event)->assertServiceUnavailable();

	$this->payments->failing = false;
	sendStripeEvent($event)->assertOk()->assertSeeText('Amount mismatch, refunded');

	expect($this->payments->refunded)->toBe([$order->payment_intent_id]);
});

it('records a failed payment and keeps the order unpaid', function (): void {
	$order = Order::factory()->awaitingPayment()->create();

	sendStripeEvent(stripeEvent('payment_intent.payment_failed', $order))->assertOk();

	expect($order->fresh()->status)->toBe(OrderStatus::AwaitingPayment);
	expect($order->events()->sole())
		->type->toBe(OrderEventType::PaymentFailed)
		->meta->toBe(['reason' => 'Your card has insufficient funds.']);
});

it('ignores events for payments it didn’t create, and other event types', function (): void {
	$order = Order::factory()->awaitingPayment()->create();
	$stranger = stripeEvent('payment_intent.succeeded', $order, ['id' => 'pi_not_ours']);
	$other = [...stripeEvent('payment_intent.succeeded', $order), 'type' => 'charge.refunded'];

	sendStripeEvent($stranger)->assertOk()->assertSeeText('Unknown payment intent');
	sendStripeEvent($other)->assertOk()->assertSeeText('Ignored');

	expect($order->fresh()->status)->toBe(OrderStatus::AwaitingPayment);
});

it('prunes checkouts nobody paid for within a day', function (): void {
	$stale = Order::factory()->awaitingPayment()->create(['created_at' => now()->subDays(2)]);
	$fresh = Order::factory()->awaitingPayment()->create(['created_at' => now()->subHours(3)]);
	$paid = Order::factory()->pending()->create(['created_at' => now()->subDays(5)]);

	$this->artisan('model:prune', ['--model' => [Order::class]])->assertSuccessful();

	expect(Order::query()->pluck('id')->all())->toEqualCanonicalizing([$fresh->id, $paid->id])
		->and(Order::query()->find($stale->id))->toBeNull()
		->and($this->payments->cancelled)->toBe([$stale->payment_intent_id]);
});

it('prunes a checkout that never got a PaymentIntent without asking Stripe', function (): void {
	$order = Order::factory()->awaitingPayment()->create(['payment_intent_id' => null, 'created_at' => now()->subDays(2)]);
	$this->payments->failing = true;

	$this->artisan('model:prune', ['--model' => [Order::class]])->assertSuccessful();

	expect(Order::query()->find($order->id))->toBeNull();
});

it('keeps an unpaid checkout and reports it when its PaymentIntent can’t be closed', function (string $problem, string $exception): void {
	Exceptions::fake();
	$order = Order::factory()->awaitingPayment()->create(['created_at' => now()->subDays(2)]);
	match ($problem) {
		'paid' => $this->payments->taken[] = $order->payment_intent_id,
		'stripe down' => $this->payments->failing = true,
	};

	$this->artisan('model:prune', ['--model' => [Order::class]])->assertSuccessful();

	expect(Order::query()->find($order->id))->not->toBeNull();
	Exceptions::assertReported($exception);
})->with([
	'Stripe took the payment' => ['paid', PaymentAlreadyTaken::class],
	'Stripe is down' => ['stripe down', PaymentProviderUnavailable::class],
]);
