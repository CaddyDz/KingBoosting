<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
	private $roles = [
		'Admin' => [
			'lock orders',
			'unlock orders'
		],
		'Moderator' => [
			'lock orders',
			'unlock orders'
		],
		'Booster' => [
			'lock orders',
			'unlock orders'
		],
		'Member' => [
			'lock orders',
			'unlock orders'
		],
	];

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		foreach ($this->roles as $role => $permissions) {
			$role = Role::create(['name' => $role])
						->givePermissionTo('Access Members Area');
			$role->givePermissionTo($permissions);
		}
	}
}
