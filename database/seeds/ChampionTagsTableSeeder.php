<?php

use App\ChampionTag;
use Illuminate\Database\Seeder;

class ChampionTagsTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$tags = [
			"Fighter",
			"Tank",
			"Mage",
			"Assassin",
			"Support",
			"Marksman",
		];
		foreach ($tags as $tag) {
			ChampionTag::create([
				'name' => $tag,
			]);
		}
	}
}
