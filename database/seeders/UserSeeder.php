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
		$this->seedBooster();
		$this->seedMember();
		foreach ($this->roles as $role => $many) {
			$this->seedUsers($role, $many);
		}
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

	public function seedBooster(): void
	{
		$booster = User::factory()->create([
			'username' => 'Booster',
			'email' => 'booster@kingboosting.com'
		]);
		// Toss a coin whether or not the booster is also a coach
		$booster->assignRole('Booster');
	}

	public function seedMember(): void
	{
		$member = User::factory()->create([
			'username' => 'User',
			'email' => 'user@kingboosting.com'
		]);
		$member->assignRole('Member');
	}

	public function seedUsers(string $role, int $number): void
	{
		User::factory($number)->create()->each(
			fn ($user) =>
			$user->assignRole($role)
		);
	}
}
