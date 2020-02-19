<?php

use App\Order;
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
        $admin = User::create([
            'username' => 'Admin',
            'first_name' => 'King',
            'last_name' => 'Boosting',
            'email' => 'admin@kingboosting.com',
            'email_verified_at' => now(),
            'password' => bcrypt('*iUJA2m%Ey67')
        ]);
        $admin->orders()->createMany(factory(Order::class, rand(3, 20))->make()->toArray());
        // Create 50 members
        factory(User::class, 50)->create()->each(function ($user) {
            $user->assignRole('Member');
            $user->orders()->createMany(factory(Order::class, rand(3, 20))->make()->toArray());
        });
        // Create 25 boosters
        factory(User::class, 25)->create()->each(function ($user) {
            $user->assignRole('Booster');
            $user->orders()->createMany(factory(Order::class, rand(3, 20))->make()->toArray());
        });
        // Create 5 moderators
        factory(User::class, 5)->create()->each(function ($user) {
            $user->assignRole('Moderator');
            $user->orders()->createMany(factory(Order::class, rand(3, 20))->make()->toArray());
        });
    }
}
