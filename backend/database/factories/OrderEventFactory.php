<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\{Order, OrderEvent};
use App\Enums\{OrderEventType, OrderStatus};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderEvent>
 */
class OrderEventFactory extends Factory
{
	/**
	 * Define the model's default state: the order was created at checkout.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'order_id' => Order::factory(),
			'actor_id' => null,
			'type' => OrderEventType::Created,
			'from_status' => null,
			'to_status' => OrderStatus::AwaitingPayment,
			'meta' => null,
		];
	}

	/**
	 * A status change from one status to another.
	 */
	public function transition(OrderEventType $type, ?OrderStatus $from, OrderStatus $to): static
	{
		return $this->state([
			'type' => $type,
			'from_status' => $from,
			'to_status' => $to,
		]);
	}
}
