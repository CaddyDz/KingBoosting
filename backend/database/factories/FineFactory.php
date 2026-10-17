<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\{Fine, User};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Fine>
 */
class FineFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'booster_id' => User::factory()->booster(),
			'issued_by' => User::factory()->admin(),
			'amount_cents' => fake()->numberBetween(2, 20) * 100,
			'reason' => fake()->randomElement([
				'Started the order more than a day late',
				'No message to the customer for 48 hours',
				'Played on the account outside the agreed hours',
				'Left the order without releasing it',
			]),
		];
	}
}
