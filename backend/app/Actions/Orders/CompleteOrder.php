<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\Models\{Order, User};
use App\Enums\{OrderEventType, OrderStatus};

class CompleteOrder
{
	public function __construct(private OrderTransition $transition) {}

	public function handle(User $booster, Order $order): Order
	{
		return $this->transition->run(
			$booster,
			$order,
			'complete',
			OrderEventType::Completed,
			fn (): array => [
				'status' => OrderStatus::Completed,
				'completed_at' => now(),
			],
		);
	}
}
