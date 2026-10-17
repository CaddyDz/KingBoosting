<?php

declare(strict_types=1);

namespace App\Pricing;

/**
 * A rank table flattened into steps, lowest first: one step per division, or one for a tier without divisions.
 * A step's `cents` is what that rank costs: climbing into it on a ladder, or one game or win played from it.
 */
final readonly class Ladder
{
	/**
	 * @param  list<array{tier: string, tier_name: string, division: string|null, name: string, cents: int}>  $steps
	 * @param  array<string, array<string, mixed>>  $tiers  the table's tiers by key
	 */
	private function __construct(public array $steps, public array $tiers) {}

	/**
	 * @param  array{tiers: list<array<string, mixed>>}  $table
	 */
	public static function fromTable(array $table): self
	{
		$steps = [];
		$tiers = [];
		foreach ($table['tiers'] as $tier) {
			$tiers[$tier['key']] = $tier;
			if (isset($tier['divisions'])) {
				foreach ($tier['divisions'] as $division => $cents) {
					$steps[] = ['tier' => $tier['key'], 'tier_name' => $tier['name'], 'division' => (string) $division, 'name' => "{$tier['name']} {$division}", 'cents' => $cents];
				}
			} else {
				$steps[] = ['tier' => $tier['key'], 'tier_name' => $tier['name'], 'division' => null, 'name' => $tier['name'], 'cents' => $tier['cents']];
			}
		}

		return new self($steps, $tiers);
	}

	/**
	 * @return list<string>
	 */
	public function tierKeys(): array
	{
		return array_keys($this->tiers);
	}

	/**
	 * @return list<string> the tier's divisions, or none for a tier like Master+
	 */
	public function divisions(string $tier): array
	{
		return array_map('strval', array_keys($this->tiers[$tier]['divisions'] ?? []));
	}

	/**
	 * The step for a rank, or null when the tier or division doesn't exist.
	 */
	public function position(string $tier, ?string $division): ?int
	{
		foreach ($this->steps as $index => $step) {
			if ($step['tier'] === $tier && $step['division'] === ($this->divisions($tier) === [] ? null : $division)) {
				return $index;
			}
		}

		return null;
	}

	/**
	 * @return array{tier: string, tier_name: string, division: string|null, name: string, cents: int}
	 */
	public function step(int $position): array
	{
		return $this->steps[$position];
	}
}
