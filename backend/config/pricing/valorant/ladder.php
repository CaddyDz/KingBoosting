<?php

declare(strict_types=1);

/*
 * Valorant rank ladder: rank boosting. Divisions go up from I to III; Immortal has none.
 * `divisions` maps each division to the price of climbing into it from the one below. The current RR doesn't change the price.
 * Ported from frontend/assets/js/valorant-boosting/ranks.js (legacy) in integer euro cents.
 */

return [
	'tiers' => [
		[
			'key' => 'iron',
			'name' => 'Iron',
			'divisions' => ['I' => 970, 'II' => 970, 'III' => 970],
		],
		[
			'key' => 'bronze',
			'name' => 'Bronze',
			'divisions' => ['I' => 1160, 'II' => 1260, 'III' => 1260],
		],
		[
			'key' => 'silver',
			'name' => 'Silver',
			'divisions' => ['I' => 1360, 'II' => 1460, 'III' => 1460],
		],
		[
			'key' => 'gold',
			'name' => 'Gold',
			'divisions' => ['I' => 1760, 'II' => 1960, 'III' => 1960],
		],
		[
			'key' => 'platinum',
			'name' => 'Platinum',
			'divisions' => ['I' => 2740, 'II' => 3000, 'III' => 3000],
		],
		[
			'key' => 'diamond',
			'name' => 'Diamond',
			'divisions' => ['I' => 3200, 'II' => 3400, 'III' => 3600],
		],
		['key' => 'immortal', 'name' => 'Immortal', 'cents' => 6000],
	],
];
