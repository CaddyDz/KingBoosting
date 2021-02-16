<?php

declare(strict_types=1);

namespace Database\Seeders;

use Database\Data\Permissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
	use Permissions;

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(): void
	{
		// Reset cached roles and permissions
		app()[PermissionRegistrar::class]->forgetCachedPermissions();
		foreach ($this->permissions as $permission) {
			Permission::create(['name' => $permission]);
		}
	}
}
