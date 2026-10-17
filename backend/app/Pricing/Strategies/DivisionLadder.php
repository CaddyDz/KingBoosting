<?php

declare(strict_types=1);

namespace App\Pricing\Strategies;

use Illuminate\Validation\Rule;
use App\Pricing\{Ladder, Priced, Service};

/**
 * Climb from the current rank to the desired one: the sum of every division climbed into.
 * League tables add an MMR surcharge per division (from that division's tier) and take off the LP
 * the account already has (from the current tier). Used by division and duo boosting, Valorant and Wild Rift.
 */
class DivisionLadder implements PricingStrategy
{
	use RankInput;

	public function rules(Service $service, array $input): array
	{
		$ladder = Ladder::fromTable($service->table());
		$rules = [...$this->rankRules($ladder, 'current', $input), ...$this->rankRules($ladder, 'desired', $input)];

		if ($this->hasMmr($service)) {
			$tier = $service->table()['tiers'][0];
			$rules['lp'] = ['required', 'string', Rule::in(array_keys($tier['lp']))];
			$rules['mmr'] = ['required', 'string', Rule::in(array_keys($tier['mmr']))];
		}

		return $rules;
	}

	public function errors(Service $service, array $input): array
	{
		$ladder = Ladder::fromTable($service->table());

		return $this->rankPosition($ladder, 'desired', $input) > $this->rankPosition($ladder, 'current', $input)
			? []
			: ['desired' => 'The desired rank has to be above the current one.'];
	}

	public function price(Service $service, array $input): Priced
	{
		$ladder = Ladder::fromTable($service->table());
		$from = $this->rankPosition($ladder, 'current', $input);
		$to = $this->rankPosition($ladder, 'desired', $input);
		$current = $ladder->step($from);
		$desired = $ladder->step($to);
		$climbed = array_slice($ladder->steps, $from + 1, $to - $from);
		$count = count($climbed);

		$lines = [[
			'label' => "{$current['name']} → {$desired['name']} (".($count === 1 ? '1 division' : "{$count} divisions").')',
			'cents' => array_sum(array_column($climbed, 'cents')),
		]];
		$inputs = ['current' => $this->rankInput('current', $input), 'desired' => $this->rankInput('desired', $input)];

		if ($this->hasMmr($service)) {
			$mmr = (string) $input['mmr'];
			$lp = (string) $input['lp'];
			$surcharge = array_sum(array_map(fn (array $step): int => $ladder->tiers[$step['tier']]['mmr'][$mmr], $climbed));
			$discount = $ladder->tiers[$current['tier']]['lp'][$lp];

			if ($surcharge > 0) {
				$lines[] = ['label' => "MMR: {$mmr} LP per win", 'cents' => $surcharge];
			}
			if ($discount > 0) {
				$lines[] = ['label' => "{$lp} LP already in {$current['name']}", 'cents' => -$discount];
			}
			$inputs += ['lp' => $lp, 'mmr' => $mmr];
		}

		return new Priced("{$current['name']} → {$desired['name']}", $inputs, $lines);
	}

	private function hasMmr(Service $service): bool
	{
		return isset($service->table()['tiers'][0]['mmr']);
	}
}
