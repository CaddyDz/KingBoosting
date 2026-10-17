<?php

declare(strict_types=1);

namespace App\Pricing\Strategies;

/**
 * Placement games, priced by the previous season's rank.
 */
class PerGame extends PerRank
{
	protected function noun(int $amount): string
	{
		return $amount === 1 ? 'placement game' : 'placement games';
	}
}
