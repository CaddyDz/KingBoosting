<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    private $permissions = [
		'edit orders',
		'delete orders',
		'lock orders',
		'unlock orders',
		'Access Members Area',
	];
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// Reset cached roles and permissions
		app()[PermissionRegistrar::class]->forgetCachedPermissions();
		foreach ($this->permissions as $permissions) {
			Permission::create(['name' => $permissions]);
		}
	}
}
