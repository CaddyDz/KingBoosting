<?php

declare(strict_types=1);

use App\Models\Order;
use Tests\Fakes\ScriptedStripeApi;
use Stripe\{ApiRequestor, StripeClient};
use App\Payments\{PaymentAlreadyTaken, PaymentProviderUnavailable, StripePaymentGateway};

/**
 * @param  list<array{0: int, 1: array<string, mixed>}>  $responses
 */
function stripeApi(array $responses): ScriptedStripeApi
{
	ApiRequestor::setHttpClient($api = new ScriptedStripeApi($responses));

	return $api;
}

function stripeGateway(): StripePaymentGateway
{
	return new StripePaymentGateway(new StripeClient(['api_key' => 'sk_test_gateway', 'max_network_retries' => 0]));
}

/**
 * @return array{0: int, 1: array<string, mixed>}
 */
function stripeError(int $status, string $code): array
{
	return [$status, ['error' => ['type' => 'invalid_request_error', 'code' => $code, 'message' => "Stripe says {$code}"]]];
}

/**
 * @param  array<string, mixed>  $overrides
 * @return array{0: int, 1: array<string, mixed>}
 */
function stripeIntent(string $status, array $overrides = []): array
{
	return [200, ['id' => 'pi_123', 'object' => 'payment_intent', 'status' => $status, 'client_secret' => 'pi_123_secret_abc', 'latest_charge' => null, ...$overrides]];
}

afterEach(function (): void {
	ApiRequestor::setHttpClient(null);
});

it('opens a PaymentIntent for the order’s price in euros, with every payment method the dashboard allows', function (): void {
	$api = stripeApi([stripeIntent('requires_payment_method')]);
	$order = Order::factory()->awaitingPayment()->create(['price_cents' => 13425, 'title' => 'Bronze I → Gold III']);

	$intent = stripeGateway()->createPaymentIntent($order);

	expect($intent)->id->toBe('pi_123')->clientSecret->toBe('pi_123_secret_abc');
	expect($api->requests)->sequence(fn ($request) => $request->toMatchArray([
		'method' => 'post',
		'path' => '/v1/payment_intents',
		'params' => [
			'amount' => 13425,
			'currency' => 'eur',
			'automatic_payment_methods' => ['enabled' => true],
			'description' => "King Boosting order #{$order->id}: Bronze I → Gold III",
			'metadata' => ['order_id' => (string) $order->id],
		],
	]));
});

it('reuses the idempotency key for the same order, but not for an order that took its id after a reset', function (): void {
	$api = stripeApi([stripeIntent('requires_payment_method'), stripeIntent('requires_payment_method'), stripeIntent('requires_payment_method')]);
	$this->travelTo('2026-10-18 09:00:00');
	$order = Order::factory()->awaitingPayment()->create(['id' => 41]);

	stripeGateway()->createPaymentIntent($order);
	stripeGateway()->createPaymentIntent($order);

	// demo:reset runs migrate:fresh, so the next checkout gets id 41 again
	$order->delete();
	$this->travelTo('2026-10-18 09:20:00');
	stripeGateway()->createPaymentIntent(Order::factory()->awaitingPayment()->create(['id' => 41]));

	expect(array_column($api->requests, 'idempotency_key'))->toBe([
		'order-41-1792314000-payment-intent',
		'order-41-1792314000-payment-intent',
		'order-41-1792315200-payment-intent',
	]);
});

it('cancels the order’s PaymentIntent as abandoned', function (): void {
	$api = stripeApi([stripeIntent('canceled')]);
	$order = Order::factory()->awaitingPayment()->create(['payment_intent_id' => 'pi_123']);

	stripeGateway()->cancelPaymentIntent($order);

	expect($api->requests)->sequence(fn ($request) => $request->toMatchArray([
		'method' => 'post',
		'path' => '/v1/payment_intents/pi_123/cancel',
		'params' => ['cancellation_reason' => 'abandoned'],
	]));
});

it('treats a PaymentIntent as closed when Stripe never had it, or it was already cancelled, or its payment was refunded', function (array $responses): void {
	$api = stripeApi($responses);
	$order = Order::factory()->awaitingPayment()->create(['payment_intent_id' => 'pi_123']);

	stripeGateway()->cancelPaymentIntent($order);

	expect($api->requests)->toHaveCount(count($responses));
})->with([
	'never had it' => [[stripeError(404, 'resource_missing')]],
	'already cancelled' => [[stripeError(400, 'payment_intent_unexpected_state'), stripeIntent('canceled')]],
	'refunded' => [[
		stripeError(400, 'payment_intent_unexpected_state'),
		stripeIntent('succeeded', ['latest_charge' => ['id' => 'ch_123', 'object' => 'charge', 'refunded' => true]]),
	]],
]);

it('looks the PaymentIntent up with its charge when it can’t be cancelled', function (): void {
	$api = stripeApi([stripeError(400, 'payment_intent_unexpected_state'), stripeIntent('canceled')]);
	$order = Order::factory()->awaitingPayment()->create(['payment_intent_id' => 'pi_123']);

	stripeGateway()->cancelPaymentIntent($order);

	expect($api->requests[1])->toMatchArray([
		'method' => 'get',
		'path' => '/v1/payment_intents/pi_123',
		'params' => ['expand' => ['latest_charge']],
	]);
});

it('refuses to close a PaymentIntent whose payment Stripe kept', function (array $intent): void {
	stripeApi([stripeError(400, 'payment_intent_unexpected_state'), $intent]);
	$order = Order::factory()->awaitingPayment()->create(['payment_intent_id' => 'pi_123']);

	stripeGateway()->cancelPaymentIntent($order);
})->with([
	'paid' => [stripeIntent('succeeded', ['latest_charge' => ['id' => 'ch_123', 'object' => 'charge', 'refunded' => false]])],
	'still processing' => [stripeIntent('processing')],
])->throws(PaymentAlreadyTaken::class, 'check the webhook deliveries in Stripe');

it('reports Stripe as unavailable when it fails for another reason', function (array $response): void {
	stripeApi([$response]);
	$order = Order::factory()->awaitingPayment()->create(['payment_intent_id' => 'pi_123']);

	stripeGateway()->cancelPaymentIntent($order);
})->with([
	'bad request' => [stripeError(400, 'parameter_invalid_empty')],
	'outage' => [[500, ['error' => ['type' => 'api_error', 'message' => 'Stripe is down']]]],
])->throws(PaymentProviderUnavailable::class);

it('refunds the order’s payment once, however often it’s asked', function (): void {
	$api = stripeApi([[200, ['id' => 're_123', 'object' => 'refund']]]);
	$order = Order::factory()->awaitingPayment()->create(['payment_intent_id' => 'pi_123']);

	stripeGateway()->refundPayment($order);

	expect($api->requests)->sequence(fn ($request) => $request->toMatchArray([
		'method' => 'post',
		'path' => '/v1/refunds',
		'params' => ['payment_intent' => 'pi_123', 'metadata' => ['order_id' => (string) $order->id]],
		'idempotency_key' => 'refund-pi_123',
	]));
});
