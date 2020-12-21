<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class BaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		User::create([
			'username' => 'Admin',
			'first_name' => 'King',
			'last_name' => 'Boosting',
			'email' => 'admin@kingboosting.com',
			'email_verified_at' => now(),
			'password' => bcrypt('*iUJA2m%Ey67'),
			'country' => 'TR',
		])->assignRole('Admin');
	}
}
