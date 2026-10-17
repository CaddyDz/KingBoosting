<?php

declare(strict_types=1);

namespace App\Pricing;

/**
 * What a strategy works out before the service multiplier and the options: the title, the inputs it used,
 * and the breakdown, in whole cents.
 */
final readonly class Priced
{
	/**
	 * @param  array<string, mixed>  $inputs
	 * @param  list<array{label: string, cents: int}>  $lines
	 */
	public function __construct(
		public string $title,
		public array $inputs,
		public array $lines,
	) {}

	public function subtotalCents(): int
	{
		return array_sum(array_column($this->lines, 'cents'));
	}
}
