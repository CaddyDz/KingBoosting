<?php

declare(strict_types=1);

namespace Tests\Fakes;

use App\Models\Order;
use App\Payments\{PaymentAlreadyTaken, PaymentGateway, PaymentIntent, PaymentProviderUnavailable};

/**
 * Stands in for Stripe in tests: records the intents it creates, cancels and refunds, or fails on demand.
 */
class FakePaymentGateway implements PaymentGateway
{
	/** @var array<int, int> order id => amount in cents */
	public array $intents = [];

	/** @var list<string> */
	public array $cancelled = [];

	/** @var list<string> */
	public array $refunded = [];

	/** @var list<string> PaymentIntents Stripe took the money on, so they can't be cancelled */
	public array $taken = [];

	public bool $failing = false;

	public function createPaymentIntent(Order $order): PaymentIntent
	{
		$this->failIfDown();
		$this->intents[$order->id] = $order->price_cents;

		return new PaymentIntent("pi_fake_{$order->id}", "pi_fake_{$order->id}_secret_test");
	}

	public function clientSecret(Order $order): string
	{
		return "{$order->payment_intent_id}_secret_test";
	}

	public function cancelPaymentIntent(Order $order): void
	{
		$this->failIfDown();
		if (in_array($order->payment_intent_id, $this->taken, true)) {
			throw new PaymentAlreadyTaken("{$order->payment_intent_id} succeeded");
		}
		$this->cancelled[] = (string) $order->payment_intent_id;
	}

	public function refundPayment(Order $order): void
	{
		$this->failIfDown();
		$this->refunded[] = (string) $order->payment_intent_id;
	}

	private function failIfDown(): void
	{
		if ($this->failing) {
			throw new PaymentProviderUnavailable('Stripe is down');
		}
	}
}
