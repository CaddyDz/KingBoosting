<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
	private array $games = [
		"LOL",
		"VALORANT",
		"TFT",
		"WILDRIFT",
		"LOR",
	];

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(): void
	{
		foreach ($this->games as $game) {
			Game::create([
				'name' => $game,
			]);
		}
	}
}
