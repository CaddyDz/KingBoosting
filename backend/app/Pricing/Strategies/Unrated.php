<?php

declare(strict_types=1);

namespace App\Pricing\Strategies;

use App\Pricing\{Priced, Service};

/**
 * Unrated matches: every game is a little cheaper the more games are ordered.
 */
class Unrated implements PricingStrategy
{
	public function rules(Service $service, array $input): array
	{
		return [
			'amount' => ['required', 'integer', "min:{$service->amount['min']}", "max:{$service->amount['max']}"],
		];
	}

	public function errors(Service $service, array $input): array
	{
		return [];
	}

	public function price(Service $service, array $input): Priced
	{
		$table = $service->table();
		$games = (int) $input['amount'];
		$perGame = $table['base_cents'] - $table['discount_cents_per_game'] * $games;

		return new Priced(
			$games === 1 ? '1 unrated match' : "{$games} unrated matches",
			['amount' => $games],
			[['label' => "{$games} × €".number_format($perGame / 100, 2), 'cents' => $perGame * $games]],
		);
	}
}
