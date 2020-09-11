<?php

use App\Server;
use Illuminate\Database\Seeder;

class ServersTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$regions = [
			'North America',
			'EU-West',
			'EU-Nordic & East',
			'Turkey',
			'Russia',
			'Brazil',
			'Latin America North',
			'Latin America South',
			'Oceania',
			'PBE'
		];
		foreach ($regions as $region) {
			Server::create(['region' => $region]);
		}
	}
}
