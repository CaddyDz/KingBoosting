<?php

declare(strict_types=1);

namespace App\Payments;

use App\Models\Order;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;

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
				// A retried request for the same order gets the same PaymentIntent back
				'idempotency_key' => "order-{$order->id}-payment-intent",
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
}
