<?php

declare(strict_types=1);

return [
	/*
	 * Password of every seeded demo account (admin@, moderator@, booster@, customer@kingboosting.test, ...).
	 */
	'password' => env('DEMO_PASSWORD', 'password'),

	/*
	 * Wipe and reseed the database every night with demo:reset. Only for the public demo.
	 */
	'reset_nightly' => (bool) env('DEMO_RESET_NIGHTLY', false),
];
