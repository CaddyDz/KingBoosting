<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
	private $roles = [
		'Admin',
		'Moderator',
		'Booster',
		'Member',
	];

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		foreach ($this->roles as $role) {
			$role = Role::create(['name' => $role]);
		}
	}
}
