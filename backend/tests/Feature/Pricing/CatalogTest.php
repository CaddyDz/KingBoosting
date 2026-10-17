<?php

declare(strict_types=1);

use App\Models\User;
use App\Pricing\QuoteEngine;

// Datasets load before the app boots, so read the service list straight from its config file
dataset('services', fn (): array => array_keys(require dirname(__DIR__, 3).'/config/pricing/services.php'));

it('describes a service for the order builder without any price', function (string $service): void {
	$response = $this->getJson("/api/catalog/{$service}")->assertOk();

	$response->assertJsonPath('data.key', $service)
		->assertJsonStructure(['data' => ['game' => ['value', 'label'], 'label', 'strategy', 'servers', 'options', 'defaults']]);
	// Prices only ever come from POST /api/quotes
	expect(json_encode($response->json('data')))->not->toContain('cents');
})->with('services');

it('starts every order builder on a valid quote', function (string $service): void {
	$defaults = $this->getJson("/api/catalog/{$service}")->json('data.defaults');

	expect(app(QuoteEngine::class)->quote($service, $defaults)->totalCents)->toBeGreaterThan(0);
})->with('services');

it('lists the League ranks with their divisions and bands', function (): void {
	$this->getJson('/api/catalog/division-boosting')
		->assertOk()
		->assertJsonPath('data.ranks.0', ['key' => 'iron', 'name' => 'Iron', 'divisions' => ['IV', 'III', 'II', 'I']])
		->assertJsonPath('data.ranks.6', ['key' => 'master', 'name' => 'Master+', 'divisions' => []])
		->assertJsonPath('data.lp', ['0-20', '21-40', '41-60', '61-80', '81-100'])
		->assertJsonPath('data.amount', null)
		->assertJsonPath('data.servers.0', ['key' => 'euw', 'label' => 'EU West']);
});

it('describes champion mastery with its token rules', function (): void {
	$this->getJson('/api/catalog/champion-mastery')
		->assertOk()
		->assertJsonPath('data.mastery.max_level', 7)
		->assertJsonPath('data.mastery.tokens.5-7', ['min' => 4, 'max' => 5])
		->assertJsonPath('data.champions.0', 'Aatrox')
		->assertJsonPath('data.ranks', null);
});

it('groups the services by game', function (): void {
	$this->getJson('/api/catalog')
		->assertOk()
		->assertJsonPath('data.0.value', 'lol')
		->assertJsonPath('data.0.services.0', ['key' => 'division-boosting', 'label' => 'Division boosting'])
		->assertJsonCount(3, 'data')
		->assertHeader('Cache-Control', 'max-age=300, public');
});

it('answers 404 for a service that doesn’t exist', function (): void {
	$this->getJson('/api/catalog/coaching')->assertNotFound();
});

it('lists the visible boosters and refreshes when one hides', function (): void {
	$visible = User::factory()->booster()->create(['username' => 'alpha']);
	$hiding = User::factory()->booster()->create(['username' => 'bravo']);
	User::factory()->booster()->hidden()->create(['username' => 'charlie']);
	User::factory()->admin()->create(['username' => 'admin']);

	$this->getJson('/api/boosters')->assertOk()->assertJsonPath('data', [
		['id' => $visible->id, 'username' => 'alpha'],
		['id' => $hiding->id, 'username' => 'bravo'],
	]);

	$hiding->forceFill(['visible' => false])->save();

	$this->getJson('/api/boosters')->assertJsonPath('data', [['id' => $visible->id, 'username' => 'alpha']]);
});
