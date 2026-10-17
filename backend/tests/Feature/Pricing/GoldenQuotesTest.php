<?php

declare(strict_types=1);

use App\Pricing\QuoteEngine;

/*
 * docs/pricing-golden.json was recorded from the legacy storefront. Every quote in it must come out of
 * the v2 engine to the cent. The recorder stored the legacy inputs (tier names, option titles); this
 * maps them to the v2 input keys.
 */

dataset('golden quotes', function (): Generator {
	// Datasets load before the app boots, so the path is relative to this file: <repo>/docs
	$golden = json_decode((string) file_get_contents(dirname(__DIR__, 4).'/docs/pricing-golden.json'), true, flags: JSON_THROW_ON_ERROR);

	foreach ($golden['quotes'] as $index => $quote) {
		yield "{$quote['page']} #{$index}" => [$quote];
	}
});

/**
 * @param  array<string, mixed>  $quote  one golden quote
 * @return array{0: string, 1: array<string, mixed>}  the service key and the v2 input
 */
function goldenInput(array $quote): array
{
	$service = ltrim($quote['page'], '/');
	$legacy = $quote['inputs'];
	$rank = fn (array $rank): array => array_filter([
		'tier' => strtolower(rtrim($rank['tier'], '+')),
		'division' => $rank['division'] ?? null,
	]);
	$optionKeys = [
		'Appear offline on chat' => 'appear-offline',
		'Specific champions/roles' => $quote['game'] === 'valorant' ? 'specific-agents' : 'specific-champions',
		'Specific agents' => 'specific-agents',
		'Priority order (2x speed) at' => 'priority',
		'Priority order at' => 'priority',
		'With Streaming at' => 'streaming',
		'+1 Win in desired rank' => 'extra-win',
		'With coaching at' => 'coaching',
		'HIGH-MMR account at' => 'high-mmr',
		'Play with booster at' => 'play-with-booster',
		'Games instead of wins at' => 'games-instead-of-wins',
		'Net wins at' => 'net-wins',
	];

	$input = ['options' => array_map(fn (string $title): string => $optionKeys[$title], $legacy['options'])];

	if (isset($legacy['current'])) {
		$input['current'] = $rank($legacy['current']);
	}
	if (isset($legacy['desired'])) {
		$input['desired'] = $rank($legacy['desired']);
	}
	if (in_array($service, ['division-boosting', 'duoqueue-boosting'], true)) {
		$input['lp'] = $legacy['current']['lp'];
		$input['mmr'] = $legacy['current']['mmr'];
	}
	if (isset($legacy['amount'])) {
		$input['amount'] = $legacy['amount'];
	}
	if ($service === 'champion-mastery') {
		// The legacy page started on Aatrox; the champion doesn't change the price
		$input['champion'] = 'Aatrox';
		$input['level'] = ['current' => $legacy['currentTier'], 'desired' => $legacy['desiredTier']];
		$input['tokens'] = $legacy['tokens'];
	}

	return [$service, $input];
}

it('reproduces the legacy price to the cent', function (array $quote): void {
	[$service, $input] = goldenInput($quote);

	$priced = app(QuoteEngine::class)->quote($service, $input);

	expect($priced->totalCents)->toBe($quote['cents'])
		->and(array_sum(array_column($priced->lines, 'cents')))->toBe($quote['cents']);
})->with('golden quotes');
