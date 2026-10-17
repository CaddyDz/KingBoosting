<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\Models\{Order, User};
use App\Enums\{OrderEventType, OrderStatus};

/**
 * Hand the order back to the jobs board: the booster drops it, or staff take it off them.
 */
class ReleaseOrder
{
	public function __construct(private OrderTransition $transition) {}

	public function handle(User $actor, Order $order): Order
	{
		return $this->transition->run(
			$actor,
			$order,
			'release',
			OrderEventType::Released,
			fn (): array => [
				'status' => OrderStatus::Pending,
				'booster_id' => null,
				'claimed_at' => null,
			],
			fn (Order $locked): array => ['booster_id' => $locked->booster_id],
		);
	}
}
