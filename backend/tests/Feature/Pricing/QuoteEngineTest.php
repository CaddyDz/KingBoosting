<?php

declare(strict_types=1);

use App\Pricing\QuoteEngine;
use Illuminate\Validation\ValidationException;

function quoteCents(string $service, array $input): int
{
	return app(QuoteEngine::class)->quote($service, $input)->totalCents;
}

it('prices Master+ instead of failing like the legacy page did', function (): void {
	// 220.00 to reach Master, + 10.00 MMR surcharge, − 35.00 for 81-100 LP in Diamond I, + 35% options
	expect(quoteCents('division-boosting', [
		'current' => ['tier' => 'diamond', 'division' => 'I'],
		'desired' => ['tier' => 'master'],
		'lp' => '81-100',
		'mmr' => '19-21',
		'options' => ['priority', 'streaming'],
	]))->toBe(26325);
});

it('prices ranks without divisions at their own price', function (): void {
	expect(quoteCents('placement-boosting', ['current' => ['tier' => 'unranked'], 'amount' => 10]))->toBe(2850)
		->and(quoteCents('ranked-wins-boosting', ['current' => ['tier' => 'master'], 'amount' => 2]))->toBe(8000)
		->and(quoteCents('valorant-placement-boosting', ['current' => ['tier' => 'immortal'], 'amount' => 1]))->toBe(6000);
});

it('climbs to Immortal instead of summing the ranks below it', function (): void {
	expect(quoteCents('valorant-boosting', [
		'current' => ['tier' => 'diamond', 'division' => 'III'],
		'desired' => ['tier' => 'immortal'],
		'options' => ['streaming'],
	]))->toBe(6900);
});

it('prices champion mastery with its token rules', function (): void {
	$mastery = fn (int $from, int $to, ?int $tokens = null): int => quoteCents('champion-mastery', [
		'champion' => 'Ahri',
		'level' => ['current' => $from, 'desired' => $to],
		'tokens' => $tokens,
	]);

	expect($mastery(1, 4))->toBe(520 + 1000 + 1550)
		->and($mastery(5, 7, 4))->toBe(4000)
		->and($mastery(6, 7, 1))->toBe(1400);
});

it('describes the quote', function (): void {
	$quote = app(QuoteEngine::class)->quote('ranked-wins-boosting', [
		'current' => ['tier' => 'gold', 'division' => 'II'],
		'amount' => 6,
		'options' => ['play-with-booster', 'appear-offline'],
	])->toArray();

	expect($quote)->toMatchArray([
		'version' => 1,
		'game' => 'lol',
		'service' => 'ranked-wins-boosting',
		'title' => '6 ranked wins (Gold II)',
		'total_cents' => 4872,
		'currency' => 'eur',
	])->and($quote['inputs'])->toBe([
		'current' => ['tier' => 'gold', 'division' => 'II'],
		'amount' => 6,
		'options' => ['play-with-booster', 'appear-offline'],
	])->and($quote['lines'])->toBe([
		['label' => '6 × Gold II', 'cents' => 3480],
		['label' => 'Play with your booster (+40%)', 'cents' => 1392],
	]);
});

it('rejects inputs the service doesn’t allow', function (string $service, array $input, string $field): void {
	try {
		app(QuoteEngine::class)->quote($service, $input);
		$this->fail('The quote should have been refused.');
	} catch (ValidationException $exception) {
		expect($exception->errors())->toHaveKey($field);
	}
})->with([
	'unknown service' => ['coaching', [], 'service'],
	'desired below current' => ['division-boosting', ['current' => ['tier' => 'gold', 'division' => 'I'], 'desired' => ['tier' => 'silver', 'division' => 'I'], 'lp' => '0-20', 'mmr' => '21+'], 'desired'],
	'same rank' => ['wildrift-rank-boosting', ['current' => ['tier' => 'gold', 'division' => 'I'], 'desired' => ['tier' => 'gold', 'division' => 'I']], 'desired'],
	'missing LP' => ['division-boosting', ['current' => ['tier' => 'gold', 'division' => 'I'], 'desired' => ['tier' => 'platinum', 'division' => 'IV'], 'mmr' => '21+'], 'lp'],
	'division Master+ lacks' => ['division-boosting', ['current' => ['tier' => 'master', 'division' => 'I'], 'desired' => ['tier' => 'master'], 'lp' => '0-20', 'mmr' => '21+'], 'current.division'],
	'Valorant division in a League order' => ['ranked-wins-boosting', ['current' => ['tier' => 'gold', 'division' => 'III'], 'amount' => 2, 'options' => ['specific-agents']], 'options.0'],
	'too many games' => ['valorant-placement-boosting', ['current' => ['tier' => 'gold', 'division' => 'I'], 'amount' => 4], 'amount'],
	'tokens outside the rule' => ['champion-mastery', ['champion' => 'Ahri', 'level' => ['current' => 5, 'desired' => 7], 'tokens' => 3], 'tokens'],
	'unknown champion' => ['champion-mastery', ['champion' => 'Nobody', 'level' => ['current' => 1, 'desired' => 2]], 'champion'],
	'duplicate option' => ['unrated-matches', ['amount' => 5, 'options' => ['priority', 'priority']], 'options.0'],
]);

it('quotes over the API for guests', function (): void {
	$this->postJson('/api/quotes', [
		'service' => 'unrated-matches',
		'amount' => 15,
		'options' => ['net-wins'],
	])->assertOk()
		->assertJsonPath('data.total_cents', 7140)
		->assertJsonPath('data.title', '15 unrated matches');
});

it('answers 422 to an invalid quote', function (): void {
	$this->postJson('/api/quotes', ['service' => 'unrated-matches', 'amount' => 31])
		->assertUnprocessable()
		->assertJsonValidationErrors('amount');
});
