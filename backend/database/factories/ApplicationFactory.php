<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Application;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Application>
 */
class ApplicationFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'in_game_name' => fake()->userName(),
			'email' => fake()->unique()->userName().'@kingboosting.test',
			'country' => fake()->country(),
			'discord_skype' => fake()->userName().'#'.fake()->numerify('####'),
			'rank' => fake()->randomElement(['Diamond I', 'Master', 'Grandmaster', 'Challenger', 'Immortal 3', 'Radiant']),
			'languages' => fake()->randomElement(['English', 'English, French', 'English, German', 'English, Spanish']),
			'games' => fake()->randomElement(['League of Legends', 'Valorant', 'League of Legends, Wild Rift']),
			'servers' => fake()->randomElement(['EUW', 'EUW, EUNE', 'NA', 'EUW, NA']),
			'hours' => fake()->randomElement(['20 per week', '30 per week', 'Evenings and weekends']),
			'motivation' => fake()->paragraphs(2, true),
			'reviewed_at' => null,
		];
	}

	public function reviewed(): static
	{
		return $this->state(['reviewed_at' => now()]);
	}
}
