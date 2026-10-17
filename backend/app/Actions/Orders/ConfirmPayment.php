<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Enums\{OrderEventType, OrderStatus};

/**
 * Stripe says the order is paid. It goes to the booster the customer picked, if they're still a visible
 * booster, or to the jobs board. Safe to run twice: an order that's no longer awaiting payment is left alone.
 */
class ConfirmPayment
{
	public function handle(Order $order): Order
	{
		return DB::transaction(function () use ($order): Order {
			$locked = Order::query()->lockForUpdate()->findOrFail($order->getKey());

			if ($locked->status !== OrderStatus::AwaitingPayment) {
				return $locked;
			}

			$booster = $locked->booster;
			$withBooster = $booster !== null && $booster->isBooster() && $booster->visible;
			$status = $withBooster ? OrderStatus::InProgress : OrderStatus::Pending;

			$locked->forceFill([
				'status' => $status,
				'paid_at' => now(),
				'booster_id' => $withBooster ? $booster->id : null,
				'claimed_at' => $withBooster ? now() : null,
			])->save();

			$locked->recordEvent(
				OrderEventType::PaymentSucceeded,
				null,
				OrderStatus::AwaitingPayment,
				$status,
				$withBooster ? ['booster_id' => $booster->id] : [],
			);

			return $locked;
		});
	}
}
