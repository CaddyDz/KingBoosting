<?php

declare(strict_types=1);

/*
 * Wild Rift rank ladder: rank boosting, Iron to Diamond, with Emerald.
 * `divisions` maps each division to the price of climbing into it from the one below.
 * Ported from frontend/assets/js/wild-rift-boosting/tiers.js (legacy) in integer euro cents.
 */

return [
	'tiers' => [
		[
			'key' => 'iron',
			'name' => 'Iron',
			'divisions' => ['IV' => 900, 'III' => 900, 'II' => 900, 'I' => 900],
		],
		[
			'key' => 'bronze',
			'name' => 'Bronze',
			'divisions' => ['IV' => 1050, 'III' => 1050, 'II' => 1050, 'I' => 1050],
		],
		[
			'key' => 'silver',
			'name' => 'Silver',
			'divisions' => ['IV' => 1250, 'III' => 1250, 'II' => 1350, 'I' => 1450],
		],
		[
			'key' => 'gold',
			'name' => 'Gold',
			'divisions' => ['IV' => 2150, 'III' => 2150, 'II' => 2350, 'I' => 2650],
		],
		[
			'key' => 'platinum',
			'name' => 'Platinum',
			'divisions' => ['IV' => 3000, 'III' => 3250, 'II' => 3700, 'I' => 4100],
		],
		[
			'key' => 'emerald',
			'name' => 'Emerald',
			'divisions' => ['IV' => 3000, 'III' => 3250, 'II' => 3700, 'I' => 4100],
		],
		[
			'key' => 'diamond',
			'name' => 'Diamond',
			'divisions' => ['IV' => 4800, 'III' => 8600, 'II' => 11850, 'I' => 17150],
		],
	],
];
