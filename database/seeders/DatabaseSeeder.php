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
		$this->call([
			PermissionSeeder::class,
			RoleSeeder::class,
		]);
		app()->isLocal() ? $this->call([
			UserSeeder::class,
			GameSeeder::class,
		]) : $this->call(BaseSeeder::class);
	}
}
