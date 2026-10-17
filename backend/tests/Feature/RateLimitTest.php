<?php

declare(strict_types=1);

use App\Models\Order;

it('limits price checks to 120 a minute for each visitor', function (): void {
	$quote = ['service' => 'unrated-matches', 'amount' => 5];

	foreach (range(1, 120) as $check) {
		$this->postJson('/api/quotes', $quote)->assertOk();
	}

	$this->postJson('/api/quotes', $quote)->assertTooManyRequests();
	// Another visitor isn't affected
	$this->withServerVariables(['REMOTE_ADDR' => '203.0.113.9'])->postJson('/api/quotes', $quote)->assertOk();
});

it('limits chat messages to 30 a minute for each person', function (): void {
	$order = Order::factory()->inProgress()->create();
	$this->actingAs($order->customer);

	foreach (range(1, 30) as $message) {
		$this->postJson("/api/orders/{$order->id}/messages", ['body' => "Message {$message}"])->assertCreated();
	}

	$this->postJson("/api/orders/{$order->id}/messages", ['body' => 'One too many'])->assertTooManyRequests();
	// The booster on the same order can still answer
	$this->actingAs($order->booster)->postJson("/api/orders/{$order->id}/messages", ['body' => 'Calm down'])->assertCreated();
});
