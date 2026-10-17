<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\Models\{Order, User};
use App\Enums\{OrderEventType, OrderStatus};
use Illuminate\Validation\ValidationException;

/**
 * Staff give a pending order to a booster.
 */
class AssignOrder
{
	public function __construct(private OrderTransition $transition) {}

	public function handle(User $staff, Order $order, User $booster): Order
	{
		if (! $booster->isBooster()) {
			throw ValidationException::withMessages(['booster_id' => 'Orders can only be assigned to boosters.']);
		}

		return $this->transition->run(
			$staff,
			$order,
			'assign',
			OrderEventType::Assigned,
			fn (): array => [
				'status' => OrderStatus::InProgress,
				'booster_id' => $booster->getKey(),
				'claimed_at' => now(),
			],
			fn (): array => ['booster_id' => $booster->getKey()],
		);
	}
}
