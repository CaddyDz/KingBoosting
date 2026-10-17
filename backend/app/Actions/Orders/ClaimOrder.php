<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\Models\{Order, User};
use App\Exceptions\OrderConflict;
use Illuminate\Support\Facades\{DB, Gate};
use App\Enums\{OrderEventType, OrderStatus};

/**
 * A booster takes a pending order from the jobs board.
 */
class ClaimOrder
{
	public function handle(User $booster, Order $order): Order
	{
		Gate::forUser($booster)->authorize('claim', $order);

		return DB::transaction(function () use ($booster, $order): Order {
			// One conditional UPDATE, so two boosters can't both win the same order.
			$claimed = Order::query()
				->whereKey($order->getKey())
				->where('status', OrderStatus::Pending)
				->whereNull('booster_id')
				->update([
					'status' => OrderStatus::InProgress,
					'booster_id' => $booster->getKey(),
					'claimed_at' => now(),
					'updated_at' => now(),
				]);

			if ($claimed === 0) {
				throw new OrderConflict('This order has already been claimed.');
			}

			$order->refresh()->recordEvent(OrderEventType::Claimed, $booster, OrderStatus::Pending, OrderStatus::InProgress);

			return $order;
		});
	}
}
