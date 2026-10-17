<?php

declare(strict_types=1);

/*
 * Wild Rift placement games: the price per game, by the previous season's rank, on the Wild Rift ladder.
 *
 * The legacy page (wildrift-placement-boosting.vue) imported the Valorant placement table by mistake. v2 prices
 * Wild Rift placements like League of Legends placements (lol/placements.php) instead. Emerald, which the legacy
 * League tables don't have, sits between Platinum and Diamond.
 */

return [
	'tiers' => [
		['key' => 'unranked', 'name' => 'Unranked', 'cents' => 285],
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
			'divisions' => ['IV' => 285, 'III' => 285, 'II' => 285, 'I' => 285],
		],
		[
			'key' => 'gold',
			'name' => 'Gold',
			'divisions' => ['IV' => 380, 'III' => 380, 'II' => 380, 'I' => 380],
		],
		[
			'key' => 'platinum',
			'name' => 'Platinum',
			'divisions' => ['IV' => 480, 'III' => 480, 'II' => 480, 'I' => 480],
		],
		[
			'key' => 'emerald',
			'name' => 'Emerald',
			'divisions' => ['IV' => 625, 'III' => 625, 'II' => 625, 'I' => 625],
		],
		[
			'key' => 'diamond',
			'name' => 'Diamond',
			'divisions' => ['IV' => 775, 'III' => 775, 'II' => 775, 'I' => 775],
		],
	],
];
