<?php

declare(strict_types=1);

use App\Enums\Game;

/*
 * The price tables in config/pricing are data, ported by hand from the legacy storefront.
 * These checks make sure every rank has a price and nothing is missing or out of order.
 */

dataset('rank tables', [
	'lol ladder' => ['lol.ladder', ['IV', 'III', 'II', 'I']],
	'lol placements' => ['lol.placements', ['IV', 'III', 'II', 'I']],
	'lol wins' => ['lol.wins', ['IV', 'III', 'II', 'I']],
	'valorant ladder' => ['valorant.ladder', ['I', 'II', 'III']],
	'valorant placements' => ['valorant.placements', ['I', 'II', 'III']],
	'wildrift ladder' => ['wildrift.ladder', ['IV', 'III', 'II', 'I']],
	'wildrift placements' => ['wildrift.placements', ['IV', 'III', 'II', 'I']],
	'wildrift wins' => ['wildrift.wins', ['IV', 'III', 'II', 'I']],
]);

it('prices every rank in whole cents', function (string $table, array $divisions): void {
	$tiers = config("pricing.{$table}.tiers");

	expect($tiers)->toBeArray()->not->toBeEmpty();
	expect(array_column($tiers, 'key'))->toEqual(array_unique(array_column($tiers, 'key')));

	foreach ($tiers as $tier) {
		expect($tier)->toHaveKeys(['key', 'name'])
			->and(isset($tier['divisions']) xor isset($tier['cents']))->toBeTrue("{$tier['name']} needs either divisions or a single price");

		if (isset($tier['divisions'])) {
			// Every division, in climbing order, with no gaps
			expect(array_keys($tier['divisions']))->toBe($divisions, "{$tier['name']} divisions");
			foreach ($tier['divisions'] as $cents) {
				expect($cents)->toBeInt()->toBeGreaterThan(0);
			}
		} else {
			expect($tier['cents'])->toBeInt()->toBeGreaterThan(0);
		}
	}
})->with('rank tables');

it('has the same MMR and LP bands in every League tier', function (): void {
	$tiers = config('pricing.lol.ladder.tiers');
	$mmrBands = array_keys($tiers[0]['mmr']);
	$lpBands = array_keys($tiers[0]['lp']);

	expect($mmrBands)->toBe(['21+', '19-21', '17-19', '15-17', '13-15', '11-13', '9-11', '6-9', '1-6'])
		->and($lpBands)->toBe(['0-20', '21-40', '41-60', '61-80', '81-100']);

	foreach ($tiers as $tier) {
		expect(array_keys($tier['mmr']))->toBe($mmrBands, "{$tier['name']} MMR bands")
			->and(array_keys($tier['lp']))->toBe($lpBands, "{$tier['name']} LP bands")
			// Gaining fewer LP per win costs more, and having more LP already costs less
			->and(array_values($tier['mmr']))->toBe(collect($tier['mmr'])->sort()->values()->all())
			->and(array_values($tier['lp']))->toBe(collect($tier['lp'])->sort()->values()->all());

		foreach ([...$tier['mmr'], ...$tier['lp']] as $cents) {
			expect($cents)->toBeInt()->toBeGreaterThanOrEqual(0);
		}
	}
});

it('prices every mastery level and token rule', function (): void {
	$mastery = config('pricing.lol.mastery');

	expect(array_keys($mastery['levels']))->toBe(range(1, $mastery['max_level']));
	foreach ($mastery['levels'] as $cents) {
		expect($cents)->toBeInt()->toBeGreaterThan(0);
	}
	foreach ($mastery['tokens'] as $rule) {
		expect($rule['min'])->toBeGreaterThanOrEqual(1)->toBeLessThanOrEqual($rule['max']);
	}
	expect($mastery['tokens']['5-7']['cents_per_token'])->toBeInt()->toBeGreaterThan(0);
});

it('never prices an unrated match at zero or less', function (): void {
	$unrated = config('pricing.valorant.unrated');
	$maxGames = config('pricing.services.unrated-matches.amount.max');

	expect($unrated['base_cents'] - $unrated['discount_cents_per_game'] * $maxGames)->toBeGreaterThan(0);
});

it('describes every service completely', function (): void {
	$strategies = ['division-ladder', 'per-game', 'per-win', 'mastery', 'unrated'];

	foreach (config('pricing.services') as $key => $service) {
		expect(Game::tryFrom($service['game']))->not->toBeNull("{$key} game")
			->and($service['strategy'])->toBeIn($strategies)
			->and(config("pricing.{$service['table']}"))->toBeArray("{$key} table")
			->and(str_starts_with($service['table'], $service['game'].'.'))->toBeTrue("{$key} uses another game's table")
			->and(config("pricing.servers.{$service['game']}"))->not->toBeEmpty();

		$optionKeys = array_column($service['options'], 'key');
		expect($optionKeys)->toBe(array_values(array_unique($optionKeys)));
		foreach ($service['options'] as $option) {
			expect($option['percent'])->toBeInt()->toBeGreaterThan(-100)
				->and($option['label'])->not->toBeEmpty();
		}

		if (in_array($service['strategy'], ['per-game', 'per-win', 'unrated'], true)) {
			expect($service['amount']['min'])->toBeGreaterThanOrEqual(1)
				->toBeLessThanOrEqual($service['amount']['default'])
				->and($service['amount']['default'])->toBeLessThanOrEqual($service['amount']['max']);
		}
	}
});
