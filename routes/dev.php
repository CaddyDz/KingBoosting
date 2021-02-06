<?php

declare(strict_types=1);

Route::get('/debug-sentry', function () {
	throw new Exception('My first Sentry error!');
});

Route::get('/mailable', function () {
	$faker = Faker\Factory::create();
	$details = [
		'name' => $faker->name,
		'email' => $faker->email,
		'subject' => $faker->realText(20),
		'message' => $faker->realText(50),
	];

	return new App\Mail\ContactMail($details);
});
