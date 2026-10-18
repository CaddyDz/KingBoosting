<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\Models\Order;
use App\Payments\PaymentGateway;
use Illuminate\Support\Facades\Log;

/**
 * Stripe took an amount that isn't the order's price. The order stays unpaid, the money goes back, and the
 * customer is told to pay again.
 */
class RefundMismatchedPayment
{
	public function __construct(private PaymentGateway $payments, private RecordPaymentFailure $recordFailure) {}

	public function handle(Order $order, int $amountCents, string $currency): void
	{
		Log::error('Stripe payment amount differs from the order price', [
			'order' => $order->id,
			'expected' => [$order->price_cents, 'eur'],
			'received' => [$amountCents, $currency],
		]);

		$this->payments->refundPayment($order);

		$this->recordFailure->handle($order, 'The amount charged didn\'t match the order price, so we refunded it.');
	}
}
