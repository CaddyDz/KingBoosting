<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use RuntimeException;
use Illuminate\Database\Seeder;

class BaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(): void
	{
		User::create([
			'username' => 'Admin',
			'first_name' => 'King',
			'last_name' => 'Boosting',
			'email' => config('site.admin.email'),
			'email_verified_at' => now(),
			'password' => bcrypt($this->password()),
			'country' => 'TR',
		])->assignRole('Admin');
	}

	/**
	 * The admin password comes from ADMIN_PASSWORD, with a fallback only for local installs.
	 */
	private function password(): string
	{
		$password = config('site.admin.password');
		if (filled($password)) {
			return $password;
		}
		if (app()->isLocal()) {
			return 'password';
		}

		throw new RuntimeException('Set ADMIN_PASSWORD before seeding outside the local environment.');
	}
}
