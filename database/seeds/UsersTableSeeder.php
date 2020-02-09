<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $god = User::create([
            'name' => 'Admin',
            'email' => 'admin@kingboosting.com',
            'email_verified_at' => now(),
            'password' => bcrypt('*iUJA2m%Ey67')
        ]);
        // Grant Super Admin role to god
        // Create 50 members
        factory(User::class, 50)->create()->each(function ($user) {
            $user->assignRole('Member');
        });
        // Create 25 boosters
        factory(User::class, 25)->create()->each(function ($user) {
            $user->assignRole('Booster');
        });
        // Create 5 moderators
        factory(User::class, 5)->create()->each(function ($user) {
            $user->assignRole('Moderator');
        });
    }
}
