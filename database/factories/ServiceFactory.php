<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Service;
use Faker\Generator as Faker;

$factory->define(Service::class, function (Faker $faker) {
    $name = $faker->sentence(4, true);
    return [
        'name' => $name,
        'image' => $faker->imageUrl(220, 220, 'abstract'),
        'description' => $faker->sentences(3, true),
        'status' => $faker->randomElement(['Popular', 'New', 'Best Value', null]),
        'slug' => sluggify($name),
        'subtitle' => $faker->sentences(3, true),
        'bg_img' => '',
    ];
});
