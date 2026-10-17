<?php

declare(strict_types=1);

use App\Enums\Role;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;

beforeEach(function (): void {
	// Sanctum only runs the session middleware for requests from a stateful origin
	$this->withHeader('Referer', config('app.url'));
});

it('signs in through the SPA flow and returns the user', function (): void {
	$user = User::factory()->booster()->create(['email' => 'booster@kingboosting.test']);

	$this->get('/sanctum/csrf-cookie')->assertNoContent()->assertCookie('XSRF-TOKEN');

	$this->postJson('/api/login', ['email' => 'booster@kingboosting.test', 'password' => 'password'])
		->assertOk()
		->assertJsonPath('data.username', $user->username);

	$this->getJson('/api/me')
		->assertOk()
		->assertJsonPath('data.id', $user->id)
		->assertJsonPath('data.role', 'booster')
		->assertJsonPath('data.visible', true);
});

it('registers a customer and signs them in', function (): void {
	$this->postJson('/api/register', [
		'username' => 'newcomer',
		'email' => 'newcomer@kingboosting.test',
		'password' => 'a-long-password',
		'password_confirmation' => 'a-long-password',
	])->assertCreated()->assertJsonPath('data.role', 'customer');

	expect(User::query()->where('username', 'newcomer')->sole()->role)->toBe(Role::Customer);
	$this->getJson('/api/me')->assertOk()->assertJsonPath('data.username', 'newcomer');
});

it('ignores a role sent with the registration', function (): void {
	$this->postJson('/api/register', [
		'username' => 'sneaky',
		'email' => 'sneaky@kingboosting.test',
		'password' => 'a-long-password',
		'password_confirmation' => 'a-long-password',
		'role' => 'admin',
	])->assertCreated()->assertJsonPath('data.role', 'customer');
});

it('rejects a wrong password with a generic message', function (): void {
	User::factory()->create(['email' => 'customer@kingboosting.test']);

	$this->postJson('/api/login', ['email' => 'customer@kingboosting.test', 'password' => 'wrong'])
		->assertUnprocessable()
		->assertJsonValidationErrors(['email' => 'These credentials do not match our records.']);
	$this->getJson('/api/me')->assertUnauthorized();
});

it('throttles repeated logins', function (): void {
	User::factory()->create(['email' => 'customer@kingboosting.test']);

	foreach (range(1, 5) as $attempt) {
		$this->postJson('/api/login', ['email' => 'customer@kingboosting.test', 'password' => 'wrong'])->assertUnprocessable();
	}

	$this->postJson('/api/login', ['email' => 'customer@kingboosting.test', 'password' => 'password'])
		->assertTooManyRequests();
});

it('signs out', function (): void {
	$this->actingAs(User::factory()->create());

	$this->postJson('/api/logout')->assertNoContent();

	$this->assertGuest('web');
});

it('refuses /api/me to guests', function (): void {
	$this->getJson('/api/me')->assertUnauthorized();
});

it('resets a forgotten password through a link to the Nuxt app', function (): void {
	Notification::fake();
	$user = User::factory()->create(['email' => 'customer@kingboosting.test']);

	$this->postJson('/api/forgot-password', ['email' => 'customer@kingboosting.test'])->assertOk();
	$this->postJson('/api/forgot-password', ['email' => 'nobody@kingboosting.test'])->assertOk();

	$token = null;
	Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use ($user, &$token): bool {
		$token = $notification->token;
		$url = $notification->toMail($user)->actionUrl;

		return str_starts_with($url, config('app.frontend_url').'/reset-password?token=');
	});

	$this->postJson('/api/reset-password', [
		'token' => $token,
		'email' => 'customer@kingboosting.test',
		'password' => 'a-new-password',
		'password_confirmation' => 'a-new-password',
	])->assertOk();

	$this->postJson('/api/login', ['email' => 'customer@kingboosting.test', 'password' => 'a-new-password'])->assertOk();
});
