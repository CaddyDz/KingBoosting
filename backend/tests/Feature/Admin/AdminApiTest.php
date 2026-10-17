<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Models\{Application, Order, User};

dataset('non-admins', [
	'moderator' => fn () => User::factory()->moderator()->create(),
	'booster' => fn () => User::factory()->booster()->create(),
	'customer' => fn () => User::factory()->customer()->create(),
]);

it('lists and filters users for admins', function (): void {
	$admin = User::factory()->admin()->create(['username' => 'admin']);
	$booster = User::factory()->booster()->create(['username' => 'nightowl']);
	User::factory()->customer()->count(2)->create();
	Order::factory()->inProgress($booster)->create();

	$this->actingAs($admin);

	$this->getJson('/api/users')->assertOk()->assertJsonCount(5, 'data');
	$this->getJson('/api/users?role=booster&search=night')
		->assertOk()
		->assertJsonCount(1, 'data')
		->assertJsonPath('data.0.username', 'nightowl')
		->assertJsonPath('data.0.jobs_count', 1);
});

it('lets an admin change roles and visibility', function (): void {
	$user = User::factory()->customer()->create();
	$this->actingAs(User::factory()->admin()->create());

	$this->patchJson("/api/users/{$user->id}", ['role' => 'booster'])->assertOk()->assertJsonPath('data.role', 'booster');
	$this->patchJson("/api/users/{$user->id}", ['visible' => false])->assertOk()->assertJsonPath('data.visible', false);

	expect($user->fresh())->role->toBe(Role::Booster)->visible->toBeFalse();
});

it('won’t let an admin change their own role', function (): void {
	$admin = User::factory()->admin()->create();

	$this->actingAs($admin)->patchJson("/api/users/{$admin->id}", ['role' => 'customer'])->assertForbidden();

	expect($admin->fresh()->role)->toBe(Role::Admin);
});

it('only gives boosters a visibility setting', function (): void {
	$customer = User::factory()->customer()->create();

	$this->actingAs(User::factory()->admin()->create())->patchJson("/api/users/{$customer->id}", ['visible' => false])->assertForbidden();
});

it('lets boosters hide themselves', function (): void {
	$booster = User::factory()->booster()->create();

	$this->actingAs($booster)->putJson('/api/me/visibility', ['visible' => false])
		->assertOk()
		->assertJsonPath('data.visible', false);
	$this->getJson('/api/boosters')->assertJsonCount(0, 'data');

	$this->actingAs(User::factory()->customer()->create())->putJson('/api/me/visibility', ['visible' => false])->assertForbidden();
});

it('takes booster applications from anyone and shows them to admins', function (): void {
	$this->postJson('/api/applications', Application::factory()->make()->only([
		'in_game_name', 'email', 'country', 'discord_skype', 'rank', 'languages', 'games', 'servers', 'hours', 'motivation',
	]))->assertCreated();
	$reviewed = Application::factory()->reviewed()->create();

	$this->actingAs(User::factory()->admin()->create());
	$response = $this->getJson('/api/applications')->assertOk()->assertJsonCount(2, 'data');
	$unread = $response->json('data.0.id');
	expect($response->json('data.1.id'))->toBe($reviewed->id);

	$this->postJson("/api/applications/{$unread}/review")->assertOk();
	expect(Application::query()->whereNull('reviewed_at')->exists())->toBeFalse();
});

it('validates applications', function (): void {
	$this->postJson('/api/applications', ['email' => 'not-an-email', 'motivation' => 'too short'])
		->assertUnprocessable()
		->assertJsonValidationErrors(['in_game_name', 'email', 'motivation']);
});

it('reports revenue per week, orders by status and completion time', function (): void {
	$this->travelTo(now()->startOfWeek()->addDays(2)->setTime(12, 0));
	Order::factory()->completed()->create(['price_cents' => 1000, 'paid_at' => now()->subWeek(), 'claimed_at' => now()->subHours(30), 'completed_at' => now()->subHours(6)]);
	Order::factory()->paidOut()->create(['price_cents' => 2500, 'paid_at' => now(), 'claimed_at' => now()->subHours(13), 'completed_at' => now()->subHours(1)]);
	Order::factory()->pending()->create(['price_cents' => 4000, 'paid_at' => now()]);
	Order::factory()->awaitingPayment()->create(['price_cents' => 9900]);

	$response = $this->actingAs(User::factory()->admin()->create())->getJson('/api/metrics')->assertOk();

	$weeks = collect($response->json('data.revenue_per_week'));
	expect($weeks)->toHaveCount(12)
		->and($weeks->last())->toBe(['week' => now()->startOfWeek()->toDateString(), 'revenue_cents' => 6500, 'orders' => 2])
		->and($weeks[10])->toBe(['week' => now()->startOfWeek()->subWeek()->toDateString(), 'revenue_cents' => 1000, 'orders' => 1])
		->and($weeks->sum('revenue_cents'))->toBe(7500);
	$response->assertJsonPath('data.orders_by_status', [
		'awaiting_payment' => 1, 'pending' => 1, 'in_progress' => 0, 'paused' => 0, 'completed' => 1, 'paid_out' => 1,
	])->assertJsonPath('data.average_completion_hours', 18);
});

it('keeps the admin endpoints to admins', function (User $user): void {
	$target = User::factory()->booster()->create();
	$application = Application::factory()->create();
	$this->actingAs($user);

	$this->getJson('/api/users')->assertForbidden();
	$this->patchJson("/api/users/{$target->id}", ['role' => 'admin'])->assertForbidden();
	$this->getJson('/api/applications')->assertForbidden();
	$this->postJson("/api/applications/{$application->id}/review")->assertForbidden();
	$this->getJson('/api/metrics')->assertForbidden();
})->with('non-admins');
