<?php

declare(strict_types=1);

use Database\Seeders\DemoSeeder;
use Illuminate\Support\Facades\Hash;
use App\Enums\{Game, OrderStatus, Role};
use App\Models\{Order, OrderEvent, User};

it('seeds a browsable demo for every role, status and game', function (): void {
	config(['demo.password' => 'demo-secret']);

	// demo:reset is migrate:fresh plus this seeder; sqlite can't migrate:fresh inside the test's transaction
	$this->seed(DemoSeeder::class);

	foreach (['admin' => Role::Admin, 'moderator' => Role::Moderator, 'booster' => Role::Booster, 'customer' => Role::Customer] as $username => $role) {
		$user = User::query()->where('email', "{$username}@kingboosting.test")->sole();
		expect($user->role)->toBe($role)
			->and(Hash::check('demo-secret', $user->password))->toBeTrue();
	}

	expect(Order::query()->count())->toBe(40)
		->and(Order::query()->pluck('status')->unique()->sortBy('value')->values()->all())
		->toEqualCanonicalizing(OrderStatus::cases())
		->and(Order::query()->pluck('game')->unique()->values()->all())
		->toEqualCanonicalizing(Game::cases());

	// Every order has a timeline, and none of it is in the future
	expect(Order::query()->doesntHave('events')->exists())->toBeFalse()
		->and(OrderEvent::query()->where('created_at', '>', now())->exists())->toBeFalse();

	// The seeded booster has work in progress, unpaid completed orders and payout history
	$booster = User::query()->where('username', 'booster')->sole();
	expect($booster->jobs()->pluck('status')->unique()->all())
		->toContain(OrderStatus::InProgress, OrderStatus::Completed, OrderStatus::PaidOut);
});
