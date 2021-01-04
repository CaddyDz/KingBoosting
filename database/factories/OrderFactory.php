<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = Order::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition(): array
	{
		return [
			'purchase' => "",
			'service' => "division-boosting",
			'tier' => "",
			'division' => "",
			'server' => "",
			'wins' => rand(1, 10),
			'booster_id' => 1,
			'client_id' => 1,
			'status' => $this->faker->randomElement(['pending', 'progress', 'paused', 'completed', 'suspended']),
			'queue' => $this->faker->randomElement(['solo_duo', 'flex_5v5']),
			'options' => [],
			'price' => 12,
		];
	}
}
