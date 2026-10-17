<?php

declare(strict_types=1);

namespace App\Pricing;

/**
 * What the order builder needs to draw a service: the ranks, bands, slider, options, servers and
 * starting input. No prices: those only come from POST /api/quotes.
 */
class CatalogEntry
{
	public function __construct(private Catalog $catalog) {}

	/**
	 * @return array<string, mixed>
	 */
	public function describe(Service $service): array
	{
		$table = $service->table();

		return [
			'key' => $service->key,
			'game' => ['value' => $service->game->value, 'label' => $service->game->label()],
			'label' => $service->label,
			'strategy' => $service->strategy,
			'ranks' => isset($table['tiers']) ? array_map(fn (array $tier): array => [
				'key' => $tier['key'],
				'name' => $tier['name'],
				'divisions' => array_map('strval', array_keys($tier['divisions'] ?? [])),
			], $table['tiers']) : null,
			'lp' => isset($table['tiers'][0]['lp']) ? array_keys($table['tiers'][0]['lp']) : null,
			'mmr' => isset($table['tiers'][0]['mmr']) ? array_keys($table['tiers'][0]['mmr']) : null,
			'amount' => $service->amount,
			'mastery' => $service->strategy === 'mastery' ? [
				'max_level' => $table['max_level'],
				'tokens' => array_map(fn (array $rule): array => ['min' => $rule['min'], 'max' => $rule['max']], $table['tokens']),
			] : null,
			'champions' => $service->strategy === 'mastery' ? config('pricing.lol.champions') : null,
			'servers' => array_map(
				fn (string $key, string $label): array => ['key' => $key, 'label' => $label],
				array_keys($this->catalog->servers($service->game)),
				$this->catalog->servers($service->game),
			),
			'options' => $service->options,
			'defaults' => $service->defaults,
		];
	}

	/**
	 * The services grouped by game, for the storefront's navigation.
	 *
	 * @return list<array<string, mixed>>
	 */
	public function index(): array
	{
		$games = [];
		foreach ($this->catalog->services() as $service) {
			$games[$service->game->value] ??= ['value' => $service->game->value, 'label' => $service->game->label(), 'services' => []];
			$games[$service->game->value]['services'][] = ['key' => $service->key, 'label' => $service->label];
		}

		return array_values($games);
	}
}
