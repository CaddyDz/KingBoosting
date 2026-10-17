<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\Models\{Order, User};
use App\Enums\{OrderEventType, OrderStatus};

class ResumeOrder
{
	public function __construct(private OrderTransition $transition) {}

	public function handle(User $actor, Order $order): Order
	{
		return $this->transition->run(
			$actor,
			$order,
			'resume',
			OrderEventType::Resumed,
			fn (): array => ['status' => OrderStatus::InProgress],
		);
	}
}
