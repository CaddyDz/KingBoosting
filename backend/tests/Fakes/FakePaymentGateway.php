<?php

declare(strict_types=1);

namespace Tests\Fakes;

use App\Models\Order;
use App\Payments\{PaymentGateway, PaymentIntent, PaymentProviderUnavailable};

/**
 * Stands in for Stripe in tests: records the intents it creates, or fails on demand.
 */
class FakePaymentGateway implements PaymentGateway
{
	/** @var array<int, int> order id => amount in cents */
	public array $intents = [];

	public bool $failing = false;

	public function createPaymentIntent(Order $order): PaymentIntent
	{
		if ($this->failing) {
			throw new PaymentProviderUnavailable('Stripe is down');
		}
		$this->intents[$order->id] = $order->price_cents;

		return new PaymentIntent("pi_fake_{$order->id}", "pi_fake_{$order->id}_secret_test");
	}

	public function clientSecret(Order $order): string
	{
		return "{$order->payment_intent_id}_secret_test";
	}
}
