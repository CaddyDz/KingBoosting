<?php

declare(strict_types=1);

namespace App\Pricing;

use App\Enums\Game;

/**
 * One sellable service from config/pricing/services.php.
 */
final readonly class Service
{
	/**
	 * @param  array{label: string, min: int, max: int, default: int}|null  $amount  the wins or games slider
	 * @param  list<array{key: string, label: string, description: string, percent: int}>  $options
	 */
	public function __construct(
		public string $key,
		public Game $game,
		public string $label,
		public string $strategy,
		public string $table,
		public int $multiplierPct,
		public ?array $amount,
		public array $options,
	) {}

	/**
	 * @param  array<string, mixed>  $config
	 */
	public static function fromConfig(string $key, array $config): self
	{
		return new self(
			key: $key,
			game: Game::from($config['game']),
			label: $config['label'],
			strategy: $config['strategy'],
			table: $config['table'],
			multiplierPct: $config['multiplier_pct'] ?? 100,
			amount: $config['amount'] ?? null,
			options: $config['options'],
		);
	}

	/**
	 * The price table this service reads, from config/pricing.
	 *
	 * @return array<string, mixed>
	 */
	public function table(): array
	{
		return config("pricing.{$this->table}");
	}

	/**
	 * @return list<string>
	 */
	public function optionKeys(): array
	{
		return array_column($this->options, 'key');
	}

	/**
	 * @return array{key: string, label: string, description: string, percent: int}|null
	 */
	public function option(string $key): ?array
	{
		foreach ($this->options as $option) {
			if ($option['key'] === $key) {
				return $option;
			}
		}

		return null;
	}
}
