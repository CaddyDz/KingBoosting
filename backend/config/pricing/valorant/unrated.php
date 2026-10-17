<?php

declare(strict_types=1);

/*
 * Valorant unrated matches: each game costs `base_cents` minus `discount_cents_per_game` for every game ordered.
 * From pages/services/unrated-matches.vue (legacy): (4 - 4 × games / 100) euros per game.
 */

return [
	'base_cents' => 400,
	'discount_cents_per_game' => 4,
];
