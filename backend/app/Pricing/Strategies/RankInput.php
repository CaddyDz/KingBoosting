<?php

declare(strict_types=1);

namespace App\Pricing\Strategies;

use App\Pricing\Ladder;
use Illuminate\Validation\Rule;

/**
 * Validation for a rank input: `{prefix}.tier`, and `{prefix}.division` when the tier has divisions.
 */
trait RankInput
{
	/**
	 * @param  array<string, mixed>  $input
	 * @return array<string, mixed>
	 */
	private function rankRules(Ladder $ladder, string $prefix, array $input): array
	{
		$tier = data_get($input, "{$prefix}.tier");
		$divisions = is_string($tier) && in_array($tier, $ladder->tierKeys(), true) ? $ladder->divisions($tier) : [];

		return [
			$prefix => ['required', 'array'],
			"{$prefix}.tier" => ['required', 'string', Rule::in($ladder->tierKeys())],
			"{$prefix}.division" => $divisions === []
				? ['nullable', 'prohibited']
				: ['required', 'string', Rule::in($divisions)],
		];
	}

	/**
	 * @param  array<string, mixed>  $input
	 */
	private function rankPosition(Ladder $ladder, string $prefix, array $input): int
	{
		return (int) $ladder->position((string) data_get($input, "{$prefix}.tier"), data_get($input, "{$prefix}.division"));
	}

	/**
	 * @param  array<string, mixed>  $input
	 * @return array{tier: string, division: string|null}
	 */
	private function rankInput(string $prefix, array $input): array
	{
		return [
			'tier' => (string) data_get($input, "{$prefix}.tier"),
			'division' => data_get($input, "{$prefix}.division"),
		];
	}
}
