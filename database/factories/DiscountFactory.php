<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Discount;
use Faker\Generator as Faker;

$factory->define(Discount::class, function (Faker $faker) {
    return [
        'code' => str_random(8),
        'expires' => $faker->dateTime
    ];
});
