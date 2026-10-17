<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\Models\{Order, User};
use App\Enums\{OrderEventType, OrderStatus};

/**
 * The booster's share of a completed order has been paid.
 */
class MarkOrderPaidOut
{
	public function __construct(private OrderTransition $transition) {}

	public function handle(User $admin, Order $order): Order
	{
		return $this->transition->run(
			$admin,
			$order,
			'markPaidOut',
			OrderEventType::PaidOut,
			fn (): array => [
				'status' => OrderStatus::PaidOut,
				'paid_out_at' => now(),
			],
			fn (Order $locked): array => ['booster_share_cents' => $locked->boosterShareCents()],
		);
	}
}
