<?php

declare(strict_types=1);

namespace App\Payments;

use App\Models\Order;

/**
 * Stands in for Stripe on a local stack without Stripe keys, so checkout still works end to end: orders get an
 * offline PaymentIntent, and the local-only routes in routes/testing.php mark them paid. Never used in production.
 */
class OfflinePaymentGateway implements PaymentGateway
{
	private const PREFIX = 'pi_offline_';

	public function createPaymentIntent(Order $order): PaymentIntent
	{
		return new PaymentIntent(self::PREFIX.$order->id, self::PREFIX.$order->id.'_secret_offline');
	}

	public function clientSecret(Order $order): string
	{
		return "{$order->payment_intent_id}_secret_offline";
	}

	/**
	 * An offline payment has nothing open anywhere to close.
	 */
	public function cancelPaymentIntent(Order $order): void {}

	/**
	 * Offline payments are simulated for the order's own price, so there's never one to give back.
	 */
	public function refundPayment(Order $order): void {}

	/**
	 * Whether the order's payment was opened offline, and so can only be paid by simulation.
	 */
	public static function handles(Order $order): bool
	{
		return str_starts_with((string) $order->payment_intent_id, self::PREFIX);
	}
}
