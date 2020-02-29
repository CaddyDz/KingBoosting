<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Order;
use App\Service;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'status' => $faker->randomElement(['pending', 'progress', 'paused', 'completed', 'suspended']),
        'price' => $faker->randomFloat(2, 1, 99),
        'booster_id' => User::role('Booster')->inRandomOrder()->first(),
        'client_id' => User::inRandomOrder()->first(),
        'service_id' => Service::inRandomOrder()->first(),
    ];
});
