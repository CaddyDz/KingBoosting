<?php

declare(strict_types=1);

use App\Models\{Order, User};
use App\Notifications\{AdminNotice, OrderStatusChanged};

it('lists the user’s notifications with the unread count', function (): void {
	$user = User::factory()->customer()->create();
	$order = Order::factory()->for($user, 'customer')->create();
	$user->notifyNow(new AdminNotice('Welcome!'), ['database']);
	$this->travel(1)->second();
	$user->notifyNow(new OrderStatusChanged($order, 'The customer paused the order.'), ['database']);
	User::factory()->create()->notifyNow(new AdminNotice('Not yours'), ['database']);

	$this->actingAs($user)->getJson('/api/notifications')
		->assertOk()
		->assertJsonCount(2, 'data')
		->assertJsonPath('data.0.title', "Order #{$order->id}: Waiting for a booster")
		->assertJsonPath('data.0.order_id', $order->id)
		->assertJsonPath('data.1.body', 'Welcome!')
		->assertJsonPath('meta.unread_count', 2);
});

it('marks one or all notifications read', function (): void {
	$user = User::factory()->create();
	$user->notifyNow(new AdminNotice('First'), ['database']);
	$user->notifyNow(new AdminNotice('Second'), ['database']);
	$first = $user->notifications()->oldest()->first();

	$this->actingAs($user)->postJson("/api/notifications/{$first->id}/read")->assertNoContent();
	expect($user->unreadNotifications()->count())->toBe(1);

	$this->postJson('/api/notifications/read')->assertNoContent();
	expect($user->unreadNotifications()->count())->toBe(0);
});

it('can’t mark someone else’s notification', function (): void {
	$owner = User::factory()->create();
	$owner->notifyNow(new AdminNotice('Mine'), ['database']);

	$this->actingAs(User::factory()->create())
		->postJson('/api/notifications/'.$owner->notifications()->first()->id.'/read')
		->assertNotFound();
});
