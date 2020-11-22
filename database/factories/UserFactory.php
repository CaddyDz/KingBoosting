<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = User::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition(): array
	{
		return [
			'username' => $this->faker->unique()->userName,
			'first_name' => $this->faker->firstName,
			'last_name' => $this->faker->lastName,
			'social' => $this->faker->userName,
			'email' => $this->faker->unique()->safeEmail,
			'email_verified_at' => now(),
			'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
			'remember_token' => str_random(10),
			'country' => $this->faker->countryCode,
		];
	}
}
