<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\Models\{Order, User};
use App\Enums\{OrderEventType, OrderStatus};

class PauseOrder
{
	public function __construct(private OrderTransition $transition) {}

	public function handle(User $actor, Order $order): Order
	{
		return $this->transition->run(
			$actor,
			$order,
			'pause',
			OrderEventType::Paused,
			fn (): array => ['status' => OrderStatus::Paused],
		);
	}
}
