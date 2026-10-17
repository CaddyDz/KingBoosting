<?php

declare(strict_types=1);

namespace App\Pricing;

/**
 * A priced order. `toArray()` is the snapshot stored on the order and returned by POST /api/quotes.
 */
final readonly class Quote
{
	public const VERSION = 1;

	/**
	 * @param  array<string, mixed>  $inputs  the validated, normalised inputs
	 * @param  list<array{label: string, cents: int}>  $lines  the breakdown; it always adds up to $totalCents
	 * @param  list<array{key: string, label: string, percent: int}>  $options
	 */
	public function __construct(
		public Service $service,
		public string $title,
		public array $inputs,
		public array $lines,
		public array $options,
		public int $totalCents,
	) {}

	/**
	 * @return array<string, mixed>
	 */
	public function toArray(): array
	{
		return [
			'version' => self::VERSION,
			'game' => $this->service->game->value,
			'service' => $this->service->key,
			'title' => $this->title,
			'inputs' => $this->inputs,
			'lines' => $this->lines,
			'options' => $this->options,
			'total_cents' => $this->totalCents,
			'currency' => 'eur',
		];
	}
}
