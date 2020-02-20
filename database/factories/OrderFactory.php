<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'status' => $faker->randomElement(['pending', 'progress', 'paused', 'completed', 'suspended']),
        'price' => $faker->randomFloat(2, 1, 99),
    ];
});
