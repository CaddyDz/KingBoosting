<?php

declare(strict_types=1);

use App\Enums\OrderStatus;
use App\Models\{Order, User};

it('lets a booster claim a pending order', function (): void {
	$order = Order::factory()->pending()->create();

	$this->actingAs($booster = User::factory()->booster()->create())
		->postJson("/api/orders/{$order->id}/claim")
		->assertOk()
		->assertJsonPath('data.status.value', 'in_progress')
		->assertJsonPath('data.booster.id', $booster->id)
		->assertJsonPath('data.allowed_actions', ['pause', 'release', 'complete']);
});

it('answers 409 to a booster who claims an order someone else just took', function (): void {
	$order = Order::factory()->pending()->create();
	$this->actingAs(User::factory()->booster()->create())->postJson("/api/orders/{$order->id}/claim")->assertOk();

	$this->actingAs(User::factory()->booster()->create())
		->postJson("/api/orders/{$order->id}/claim")
		->assertConflict()
		->assertJsonPath('message', 'This order has already been claimed.');
});

it('refuses a claim from a customer', function (): void {
	$order = Order::factory()->pending()->create();

	$this->actingAs(User::factory()->customer()->create())->postJson("/api/orders/{$order->id}/claim")->assertForbidden();
});

it('lets staff assign a pending order to a booster', function (): void {
	$order = Order::factory()->pending()->create();
	$booster = User::factory()->booster()->create();

	$this->actingAs(User::factory()->moderator()->create())
		->postJson("/api/orders/{$order->id}/assign", ['booster_id' => $booster->id])
		->assertOk()
		->assertJsonPath('data.booster.id', $booster->id);
});

it('rejects an assignment to someone who isn’t a booster', function (): void {
	$order = Order::factory()->pending()->create();

	$this->actingAs(User::factory()->admin()->create())
		->postJson("/api/orders/{$order->id}/assign", ['booster_id' => User::factory()->customer()->create()->id])
		->assertUnprocessable()
		->assertJsonValidationErrors('booster_id');
});

it('refuses an assignment from a booster before looking at the input', function (): void {
	$order = Order::factory()->pending()->create();

	$this->actingAs(User::factory()->booster()->create())->postJson("/api/orders/{$order->id}/assign")->assertForbidden();
});

it('answers 409 to an assignment of an order that is already in progress', function (): void {
	$order = Order::factory()->inProgress()->create();

	$this->actingAs(User::factory()->admin()->create())
		->postJson("/api/orders/{$order->id}/assign", ['booster_id' => User::factory()->booster()->create()->id])
		->assertConflict();
});

it('lets the customer pause and resume their order', function (): void {
	$order = Order::factory()->inProgress()->create();
	$this->actingAs($order->customer);

	$this->postJson("/api/orders/{$order->id}/pause")->assertOk()->assertJsonPath('data.status.value', 'paused');
	$this->postJson("/api/orders/{$order->id}/pause")->assertConflict();
	$this->postJson("/api/orders/{$order->id}/resume")->assertOk()->assertJsonPath('data.status.value', 'in_progress');
	$this->postJson("/api/orders/{$order->id}/resume")->assertConflict();
});

it('refuses to pause or resume someone else’s order', function (): void {
	$playing = Order::factory()->inProgress()->create();
	$paused = Order::factory()->paused()->create();
	$this->actingAs(User::factory()->customer()->create());

	$this->postJson("/api/orders/{$playing->id}/pause")->assertForbidden();
	$this->postJson("/api/orders/{$paused->id}/resume")->assertForbidden();
});

it('lets the booster release an order back to the jobs board', function (): void {
	$order = Order::factory()->inProgress()->create();

	$this->actingAs($order->booster)
		->postJson("/api/orders/{$order->id}/release")
		->assertOk()
		->assertJsonPath('data.status.value', 'pending')
		->assertJsonPath('data.booster', null);
});

it('refuses to release a completed order, or a customer’s release', function (): void {
	$completed = Order::factory()->completed()->create();
	$playing = Order::factory()->inProgress()->create();

	$this->actingAs($completed->booster)->postJson("/api/orders/{$completed->id}/release")->assertConflict();
	$this->actingAs($playing->customer)->postJson("/api/orders/{$playing->id}/release")->assertForbidden();
});

it('lets the booster complete their order', function (): void {
	$order = Order::factory()->inProgress()->create();

	$this->actingAs($order->booster)
		->postJson("/api/orders/{$order->id}/complete")
		->assertOk()
		->assertJsonPath('data.status.value', 'completed');
});

it('refuses to complete a paused order, or another booster’s', function (): void {
	$paused = Order::factory()->paused()->create();
	$playing = Order::factory()->inProgress()->create();

	$this->actingAs($paused->booster)->postJson("/api/orders/{$paused->id}/complete")->assertConflict();
	$this->actingAs(User::factory()->booster()->create())->postJson("/api/orders/{$playing->id}/complete")->assertForbidden();
});

it('lets an admin mark a completed order paid out', function (): void {
	$order = Order::factory()->completed()->create();

	$this->actingAs(User::factory()->admin()->create())
		->postJson("/api/orders/{$order->id}/mark-paid-out")
		->assertOk()
		->assertJsonPath('data.status.value', 'paid_out');
});

it('refuses to pay out an unfinished order, or a moderator’s payout', function (): void {
	$playing = Order::factory()->inProgress()->create();
	$completed = Order::factory()->completed()->create();

	$this->actingAs(User::factory()->admin()->create())->postJson("/api/orders/{$playing->id}/mark-paid-out")->assertConflict();
	$this->actingAs(User::factory()->moderator()->create())->postJson("/api/orders/{$completed->id}/mark-paid-out")->assertForbidden();
});

it('lets the customer update their credentials', function (): void {
	$order = Order::factory()->pending()->create();

	$this->actingAs($order->customer)
		->putJson("/api/orders/{$order->id}/credentials", ['login' => 'new-login', 'password' => 'new-password'])
		->assertOk()
		->assertJsonPath('data.credentials', ['login' => 'new-login', 'password' => 'new-password']);
	expect($order->fresh()->account_password)->toBe('new-password');
});

it('validates the credentials', function (): void {
	$order = Order::factory()->pending()->create();

	$this->actingAs($order->customer)
		->putJson("/api/orders/{$order->id}/credentials", ['login' => 'new-login'])
		->assertUnprocessable()
		->assertJsonValidationErrors('password');
});

it('refuses credential changes on a finished order, or from anyone but the customer', function (): void {
	$done = Order::factory()->completed()->create();
	$playing = Order::factory()->inProgress()->create();
	$input = ['login' => 'new-login', 'password' => 'new-password'];

	$this->actingAs($done->customer)->putJson("/api/orders/{$done->id}/credentials", $input)->assertConflict();
	$this->actingAs($playing->booster)->putJson("/api/orders/{$playing->id}/credentials", $input)->assertForbidden();
	expect($playing->fresh()->status)->toBe(OrderStatus::InProgress);
});
