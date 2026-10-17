<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\Models\Order;
use App\Enums\{OrderEventType, OrderStatus};

/**
 * Stripe refused the payment. The order stays unpaid, so the customer can try another card.
 */
class RecordPaymentFailure
{
	public function handle(Order $order, ?string $reason): void
	{
		if ($order->status !== OrderStatus::AwaitingPayment) {
			return;
		}

		$order->recordEvent(OrderEventType::PaymentFailed, null, null, null, ['reason' => $reason ?? 'The payment was declined.']);
	}
}
