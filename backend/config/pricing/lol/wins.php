<?php

declare(strict_types=1);

/*
 * League of Legends ranked wins: the price per win, by the current rank.
 * Ported from frontend/assets/js/ranked-wins-boosting/tiers.js (legacy) in integer euro cents.
 */

return [
	'tiers' => [
		[
			'key' => 'iron',
			'name' => 'Iron',
			'divisions' => ['IV' => 190, 'III' => 190, 'II' => 190, 'I' => 190],
		],
		[
			'key' => 'bronze',
			'name' => 'Bronze',
			'divisions' => ['IV' => 190, 'III' => 190, 'II' => 190, 'I' => 190],
		],
		[
			'key' => 'silver',
			'name' => 'Silver',
			'divisions' => ['IV' => 290, 'III' => 290, 'II' => 380, 'I' => 380],
		],
		[
			'key' => 'gold',
			'name' => 'Gold',
			'divisions' => ['IV' => 480, 'III' => 480, 'II' => 580, 'I' => 580],
		],
		[
			'key' => 'platinum',
			'name' => 'Platinum',
			'divisions' => ['IV' => 775, 'III' => 775, 'II' => 875, 'I' => 970],
		],
		[
			'key' => 'diamond',
			'name' => 'Diamond',
			'divisions' => ['IV' => 1170, 'III' => 1560, 'II' => 2250, 'I' => 2540],
		],
		['key' => 'master', 'name' => 'Master+', 'cents' => 4000],
	],
];
