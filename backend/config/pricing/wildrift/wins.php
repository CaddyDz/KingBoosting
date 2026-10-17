<?php

declare(strict_types=1);

/*
 * Wild Rift ranked wins: the price per win, by the current rank, on the Wild Rift ladder.
 *
 * The legacy page (wildrift-ranked-wins-boosting.vue) imported the League of Legends tiers, so these are the League
 * ranked-wins prices (lol/wins.php) on Wild Rift's ranks. Emerald, which the legacy League tables don't have,
 * climbs evenly from Platinum I to Diamond IV. Wild Rift boosting stops at Diamond, so there's no Master+.
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
			'key' => 'emerald',
			'name' => 'Emerald',
			'divisions' => ['IV' => 1010, 'III' => 1050, 'II' => 1090, 'I' => 1130],
		],
		[
			'key' => 'diamond',
			'name' => 'Diamond',
			'divisions' => ['IV' => 1170, 'III' => 1560, 'II' => 2250, 'I' => 2540],
		],
	],
];
