<?php

declare(strict_types=1);

namespace App\Payments;

/**
 * The part of a Stripe PaymentIntent the app keeps: its id, and the secret the Payment Element confirms it with.
 */
final readonly class PaymentIntent
{
	public function __construct(public string $id, public string $clientSecret) {}
}
