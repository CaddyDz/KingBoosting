<?php

declare(strict_types=1);

namespace App\Payments;

use RuntimeException;

/**
 * Stripe took the payment for an order the app still has as unpaid, usually because the webhook never arrived.
 * The order is kept so someone can check Stripe's webhook deliveries.
 */
class PaymentAlreadyTaken extends RuntimeException {}
