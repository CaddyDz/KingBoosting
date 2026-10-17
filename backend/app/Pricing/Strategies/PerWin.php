<?php

declare(strict_types=1);

namespace App\Pricing\Strategies;

/**
 * Ranked wins, priced by the current rank.
 */
class PerWin extends PerRank
{
	protected function noun(int $amount): string
	{
		return $amount === 1 ? 'ranked win' : 'ranked wins';
	}
}
