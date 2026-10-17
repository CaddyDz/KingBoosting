<?php

declare(strict_types=1);

namespace App\Pricing\Strategies;

use App\Pricing\{Ladder, Priced, Service};

/**
 * A price per game or per win, set by the account's rank, times how many the customer orders.
 * A tier without divisions (Unranked, Master+, Immortal) uses its own price; the legacy pages
 * kept charging the previous tier's division instead.
 */
abstract class PerRank implements PricingStrategy
{
	use RankInput;

	/**
	 * How the amount reads in the title, e.g. "placement games".
	 */
	abstract protected function noun(int $amount): string;

	public function rules(Service $service, array $input): array
	{
		return [
			...$this->rankRules(Ladder::fromTable($service->table()), 'current', $input),
			'amount' => ['required', 'integer', "min:{$service->amount['min']}", "max:{$service->amount['max']}"],
		];
	}

	public function errors(Service $service, array $input): array
	{
		return [];
	}

	public function price(Service $service, array $input): Priced
	{
		$ladder = Ladder::fromTable($service->table());
		$rank = $ladder->step($this->rankPosition($ladder, 'current', $input));
		$amount = (int) $input['amount'];

		return new Priced(
			"{$amount} {$this->noun($amount)} ({$rank['name']})",
			['current' => $this->rankInput('current', $input), 'amount' => $amount],
			[['label' => "{$amount} × {$rank['name']}", 'cents' => $rank['cents'] * $amount]],
		);
	}
}
