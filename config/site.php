<?php

declare(strict_types=1);

return [

	/*
	|--------------------------------------------------------------------------
	| Site owner name
	|--------------------------------------------------------------------------
	|
	| This config is used on the contact mail template for now.
	|
	*/

	'owner' => env('OWNER_NAME', 'Ked'),
	'admin' => [
		'name' => env('ADMIN_NAME', 'Ked'),
		'email' => env('ADMIN_EMAIL', 'admin@kingboosting.com'),
	],
];
