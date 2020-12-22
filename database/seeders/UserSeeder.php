<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
	private array $roles = [
		'Moderator' => 5,
		'Booster' => 10,
		'Member' => 50,
	];

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(): void
	{
		$this->seedAdmin();
		foreach ($this->roles as $role => $many) {
			$this->seedUsers($role, $many);
		}
		User::factory(10)->create();
		$member = User::factory()->create(['email' => 'amalia70@example.org']);
		$member->assignRole('Member');
		$booster = User::factory()->create(['email' => 'booster@kingboosting.com']);
		$booster->assignRole('Booster');
	}

	public function seedAdmin(): void
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

	public function seedUsers(string $role, int $number): void
	{
		User::factory($number)->create()->each(
			fn ($user) =>
			$user->assignRole($role)
		);
	}
}
