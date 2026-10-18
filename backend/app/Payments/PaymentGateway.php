<?php

declare(strict_types=1);

namespace App\Payments;

use App\Models\Order;

interface PaymentGateway
{
	/**
	 * Create (or, for the same order, return the same) PaymentIntent for the order's price.
	 *
	 * @throws PaymentProviderUnavailable
	 */
	public function createPaymentIntent(Order $order): PaymentIntent;

	/**
	 * The client secret of the order's existing PaymentIntent, to resume a checkout.
	 *
	 * @throws PaymentProviderUnavailable
	 */
	public function clientSecret(Order $order): string;

	/**
	 * Close the order's PaymentIntent before the order is deleted, so a payment page left open can't take money
	 * for an order that no longer exists. Returns quietly if it's already closed or its payment was refunded.
	 *
	 * @throws PaymentAlreadyTaken
	 * @throws PaymentProviderUnavailable
	 */
	public function cancelPaymentIntent(Order $order): void;

	/**
	 * Give back the whole payment taken on the order's PaymentIntent. Safe to call again for the same order.
	 *
	 * @throws PaymentProviderUnavailable
	 */
	public function refundPayment(Order $order): void;
}
