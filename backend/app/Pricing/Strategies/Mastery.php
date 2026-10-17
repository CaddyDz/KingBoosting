<?php

declare(strict_types=1);

namespace App\Pricing\Strategies;

use Illuminate\Validation\Rule;
use App\Pricing\{Priced, Service};

/**
 * Champion mastery: the price of every level reached, per token. Levels 6 and 7 need tokens,
 * and going from 5 straight to 7 is a flat price per token.
 */
class Mastery implements PricingStrategy
{
	public function rules(Service $service, array $input): array
	{
		$max = $service->table()['max_level'];
		$rule = $this->tokenRule($service, $input);

		return [
			'champion' => ['required', 'string', Rule::in(config('pricing.lol.champions'))],
			'level' => ['required', 'array'],
			'level.current' => ['required', 'integer', 'min:0', 'max:'.($max - 1)],
			'level.desired' => ['required', 'integer', 'max:'.$max],
			'tokens' => $rule === null
				? ['nullable', 'integer', 'in:1']
				: ['required', 'integer', "min:{$rule['min']}", "max:{$rule['max']}"],
		];
	}

	public function errors(Service $service, array $input): array
	{
		return (int) data_get($input, 'level.desired') > (int) data_get($input, 'level.current')
			? []
			: ['level.desired' => 'The desired level has to be above the current one.'];
	}

	public function price(Service $service, array $input): Priced
	{
		$table = $service->table();
		$current = (int) $input['level']['current'];
		$desired = (int) $input['level']['desired'];
		$rule = $this->tokenRule($service, $input);
		$tokens = $rule === null ? 1 : (int) $input['tokens'];
		$perToken = $rule['cents_per_token'] ?? array_sum(array_map(
			fn (int $level): int => $table['levels'][$level],
			range($current + 1, $desired),
		));
		$champion = (string) $input['champion'];

		return new Priced(
			"{$champion} mastery {$current} → {$desired}",
			['champion' => $champion, 'level' => ['current' => $current, 'desired' => $desired], 'tokens' => $tokens],
			[['label' => "Mastery {$current} → {$desired}".($tokens > 1 ? " × {$tokens} tokens" : ''), 'cents' => $perToken * $tokens]],
		);
	}

	/**
	 * The token rule for this level change, if it needs tokens.
	 *
	 * @param  array<string, mixed>  $input
	 * @return array{min: int, max: int, cents_per_token?: int}|null
	 */
	private function tokenRule(Service $service, array $input): ?array
	{
		$key = data_get($input, 'level.current').'-'.data_get($input, 'level.desired');

		return $service->table()['tokens'][$key] ?? null;
	}
}
