<?php

declare(strict_types=1);

/*
 * League of Legends champion mastery: the price of reaching each mastery level, per token.
 * Levels 6 and 7 need tokens; the customer says how many they still need. Going from 5 straight to 7
 * is a flat price per token instead of the two levels' prices.
 * Ported from frontend/assets/js/champion-mastery/tiers.js and pages/services/champion-mastery.vue (legacy).
 */

return [
	'levels' => [
		'1' => 280,
		'2' => 520,
		'3' => 1000,
		'4' => 1550,
		'5' => 2035,
		'6' => 1500,
		'7' => 1400,
	],
	'max_level' => 7,
	'tokens' => [
		'5-6' => ['min' => 1, 'max' => 2],
		'6-7' => ['min' => 1, 'max' => 3],
		'5-7' => ['min' => 4, 'max' => 5, 'cents_per_token' => 1000],
	],
];
