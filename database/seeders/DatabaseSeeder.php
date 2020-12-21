<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run(): void
	{
		if (app()->isLocal()) {
			$this->call([
				PermissionSeeder::class,
				RoleSeeder::class,
				UserSeeder::class,
			]);
		} else {
			$this->call(BaseSeeder::class);
		}
	}
}
