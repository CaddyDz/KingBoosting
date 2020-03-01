<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tier;
use App\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    $service_id = rand(1, 14);
    $purchase = function ($service_id) {
        switch ($service_id['service_id']) {
            case 1:
                return "14 wins";
            case 2:
                $from_tier = Tier::find(rand(1, 3));
                $to_tier = Tier::find(rand(4, 6));
                $from_division = $from_tier->divisions[rand(0, 3)]->name;
                $to_division = $to_tier->divisions[rand(0, 3)]->name;
                return "$from_tier->name $from_division to $to_tier->name $to_division";
            default:
                return "Nothing";
        }
    };
    return [
        'status' => $faker->randomElement(['pending', 'progress', 'paused', 'completed', 'suspended']),
        'price' => $faker->randomFloat(2, 1, 99),
        'booster_id' => $faker->optional()->passthrough(rand(6, 100)),
        'client_id' => rand(1, 600),
        'service_id' => $service_id,
        'purchase' => $purchase,
    ];
});
