<?php

declare(strict_types=1);

namespace App\Payments;

use App\Models\Order;
use Stripe\{Charge, StripeClient};
use Stripe\Exception\{ApiErrorException, InvalidRequestException};

class StripePaymentGateway implements PaymentGateway
{
	public function __construct(private StripeClient $stripe) {}

	public function createPaymentIntent(Order $order): PaymentIntent
	{
		try {
			$intent = $this->stripe->paymentIntents->create([
				'amount' => $order->price_cents,
				'currency' => 'eur',
				'automatic_payment_methods' => ['enabled' => true],
				'description' => "King Boosting order #{$order->id}: {$order->title}",
				'metadata' => ['order_id' => (string) $order->id],
			], [
				// A retried request for the same order gets the same PaymentIntent back. The creation time is in the
				// key because order ids start over after demo:reset, and Stripe remembers a key for a day.
				'idempotency_key' => "order-{$order->id}-{$order->created_at?->getTimestamp()}-payment-intent",
			]);
		} catch (ApiErrorException $exception) {
			throw new PaymentProviderUnavailable($exception->getMessage(), previous: $exception);
		}

		return new PaymentIntent($intent->id, (string) $intent->client_secret);
	}

	public function clientSecret(Order $order): string
	{
		try {
			return (string) $this->stripe->paymentIntents->retrieve((string) $order->payment_intent_id)->client_secret;
		} catch (ApiErrorException $exception) {
			throw new PaymentProviderUnavailable($exception->getMessage(), previous: $exception);
		}
	}

	public function cancelPaymentIntent(Order $order): void
	{
		$id = (string) $order->payment_intent_id;

		try {
			$this->stripe->paymentIntents->cancel($id, ['cancellation_reason' => 'abandoned']);
		} catch (InvalidRequestException $exception) {
			match ($exception->getStripeCode()) {
				// Stripe never had it, e.g. an offline payment from before the keys were set
				'resource_missing' => null,
				// Already cancelled, or past the point of cancelling
				'payment_intent_unexpected_state' => $this->ensureNothingKept($order, $id),
				default => throw new PaymentProviderUnavailable($exception->getMessage(), previous: $exception),
			};
		} catch (ApiErrorException $exception) {
			throw new PaymentProviderUnavailable($exception->getMessage(), previous: $exception);
		}
	}

	public function refundPayment(Order $order): void
	{
		$id = (string) $order->payment_intent_id;

		try {
			$this->stripe->refunds->create(
				['payment_intent' => $id, 'metadata' => ['order_id' => (string) $order->id]],
				['idempotency_key' => "refund-{$id}"],
			);
		} catch (ApiErrorException $exception) {
			throw new PaymentProviderUnavailable($exception->getMessage(), previous: $exception);
		}
	}

	/**
	 * A PaymentIntent that can't be cancelled is fine if it already was, or if its payment was refunded in full.
	 */
	private function ensureNothingKept(Order $order, string $id): void
	{
		try {
			$intent = $this->stripe->paymentIntents->retrieve($id, ['expand' => ['latest_charge']]);
		} catch (ApiErrorException $exception) {
			throw new PaymentProviderUnavailable($exception->getMessage(), previous: $exception);
		}

		$charge = $intent->latest_charge;
		if ($intent->status === 'canceled' || ($charge instanceof Charge && $charge->refunded)) {
			return;
		}

		throw new PaymentAlreadyTaken("Order #{$order->id} is unpaid, but its PaymentIntent {$id} is {$intent->status}: check the webhook deliveries in Stripe.");
	}
}
