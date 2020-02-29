<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Article;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
    $title = $faker->sentence;
    return [
        'title' => $title,
        'slug' => sluggify($title),
        'body' => $faker->paragraphs(3, true),
        'excerpt' => $faker->sentences(3, true),
        'image' => $faker->randomElement([
            '/img/blogPosts/Champion-Balance-blog.png',
            '/img/blogPosts/Mistakes-blog.jpg',
            '/img/blogPosts/Tarzaned-The-booster-blog.png'
        ])
    ];
});
