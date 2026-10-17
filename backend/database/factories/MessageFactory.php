<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\{Message, Order};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Messages default to one sent by the order's customer.
 *
 * @extends Factory<Message>
 */
class MessageFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'order_id' => Order::factory()->inProgress(),
			'sender_id' => fn (array $attributes): int => Order::query()->findOrFail($attributes['order_id'])->customer_id,
			'body' => fake()->sentence(),
			'read_at' => null,
		];
	}

	/**
	 * Sent by the booster holding the order.
	 */
	public function fromBooster(): static
	{
		return $this->state([
			'sender_id' => fn (array $attributes): ?int => Order::query()->findOrFail($attributes['order_id'])->booster_id,
		]);
	}

	/**
	 * Posted by an order action rather than a person.
	 */
	public function system(): static
	{
		return $this->state(['sender_id' => null]);
	}

	public function read(): static
	{
		return $this->state(['read_at' => now()]);
	}
}
