<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database. The app only ever runs as a demo, so this is the demo data.
	 */
	public function run(): void
	{
		$this->call(DemoSeeder::class);
	}
}
