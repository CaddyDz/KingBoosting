<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    private $roles = [
        'Moderator' => 5,
        'Booster' => 100,
        'Member' => 1000,
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
    }

    public function seedAdmin(): void
    {
        User::create([
            'username' => 'Admin',
            'first_name' => 'King',
            'last_name' => 'Boosting',
            'email' => 'admin@kingboosting.com',
            'email_verified_at' => now(),
            'password' => bcrypt('*iUJA2m%Ey67')
        ])->assignRole('Super Admin');
    }

    public function seedUsers(string $role, int $number): void
    {
        factory(User::class, $number)->create()->each(
            fn ($user) =>
            $user->assignRole($role)
        );
    }
}
