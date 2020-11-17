<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tier;
use App\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
	$service = rand(1, 19);
	$purchase = function () use ($service) {
		$from_tier = Tier::find(rand(1, 3));
		$to_tier = Tier::find(rand(4, 6));
		$from_division = $from_tier->divisions[rand(0, 3)]->name;
		$to_division = $to_tier->divisions[rand(0, 3)]->name;
		switch ($service) {
			case 1:
				return "14 wins in $from_tier->name $from_division";
			case 2:
			case 4:
				return "$from_tier->name $from_division to $to_tier->name $to_division";
			case 3:
				//
			default:
				return "Nothing";
		}
	};
	return [
		'status' => $faker->randomElement(['pending', 'progress', 'paused', 'completed', 'suspended']),
		'price' => $faker->randomFloat(2, 1, 99),
		'booster_id' => $faker->optional()->passthrough(rand(6, 100)),
		'client_id' => rand(1, 600),
		// We need to insert the service_service_type pivot table id here
		'service_service_type_id' => $service,
		'purchase' => $purchase,
	];
});
