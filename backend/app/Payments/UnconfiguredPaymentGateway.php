<?php

declare(strict_types=1);

namespace App\Payments;

use App\Models\Order;

/**
 * Used when STRIPE_SECRET is empty, e.g. a fresh clone: the rest of the app works, checkout says why it can't.
 */
class UnconfiguredPaymentGateway implements PaymentGateway
{
	public function createPaymentIntent(Order $order): PaymentIntent
	{
		throw new PaymentProviderUnavailable('Stripe isn\'t configured: set STRIPE_KEY and STRIPE_SECRET.');
	}

	public function clientSecret(Order $order): string
	{
		throw new PaymentProviderUnavailable('Stripe isn\'t configured: set STRIPE_KEY and STRIPE_SECRET.');
	}
}
