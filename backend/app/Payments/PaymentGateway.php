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
}
