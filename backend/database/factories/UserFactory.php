<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
	/**
	 * The current password being used by the factory.
	 */
	protected static ?string $password;

	/**
	 * Define the model's default state: a customer.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'username' => Str::limit(fake()->unique()->userName(), 32, ''),
			'email' => fake()->unique()->userName().'@kingboosting.test',
			'email_verified_at' => now(),
			'password' => static::$password ??= Hash::make('password'),
			'role' => Role::Customer,
			'visible' => true,
			'remember_token' => Str::random(10),
		];
	}

	public function admin(): static
	{
		return $this->state(['role' => Role::Admin]);
	}

	public function moderator(): static
	{
		return $this->state(['role' => Role::Moderator]);
	}

	public function booster(): static
	{
		return $this->state(['role' => Role::Booster]);
	}

	public function customer(): static
	{
		return $this->state(['role' => Role::Customer]);
	}

	/**
	 * A booster who opted out of the public booster list.
	 */
	public function hidden(): static
	{
		return $this->state(['visible' => false]);
	}

	/**
	 * Indicate that the model's email address should be unverified.
	 */
	public function unverified(): static
	{
		return $this->state(['email_verified_at' => null]);
	}
}
