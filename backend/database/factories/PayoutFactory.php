<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\{Payout, User};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payout>
 */
class PayoutFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		$gross = fake()->numberBetween(20, 300) * 100;

		return [
			'booster_id' => User::factory()->booster(),
			'paid_by' => User::factory()->admin(),
			'gross_cents' => $gross,
			'fines_cents' => 0,
			'amount_cents' => $gross,
			'orders_count' => fake()->numberBetween(1, 6),
			'paid_at' => now(),
		];
	}
}
