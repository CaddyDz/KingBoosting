<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Server;
use Illuminate\Database\Seeder;

class ServerSeeder extends Seeder
{
	private array $servers = [
		"North America",
		"EU-West",
		"EU-Nordic & East",
		"Turkey",
		"Russia",
		"Brazil",
		"Latin America North",
		"Latin America South",
		"Oceania",
		"PBE",
	];

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(): void
	{
		foreach ($this->servers as $server) {
			Server::create([
				'region' => $server,
			]);
		}
	}
}
