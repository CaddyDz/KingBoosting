<?php

declare(strict_types=1);

use App\Enums\OrderStatus;
use App\Models\{Order, User};
use App\Payments\{OfflinePaymentGateway, PaymentGateway, StripePaymentGateway, UnconfiguredPaymentGateway};

it('pays offline on a local stack without Stripe keys, and never in production', function (): void {
	config(['services.stripe.secret' => null]);

	app()->detectEnvironment(fn (): string => 'local');
	expect(app(PaymentGateway::class))->toBeInstanceOf(OfflinePaymentGateway::class);

	app()->detectEnvironment(fn (): string => 'production');
	expect(app(PaymentGateway::class))->toBeInstanceOf(UnconfiguredPaymentGateway::class);

	config(['services.stripe.secret' => 'sk_test_123']);
	expect(app(PaymentGateway::class))->toBeInstanceOf(StripePaymentGateway::class);
});

it('takes an order end to end without Stripe: checkout, then a simulated payment', function (): void {
	$this->app->instance(PaymentGateway::class, new OfflinePaymentGateway);
	$customer = User::factory()->customer()->create();

	$id = $this->actingAs($customer)->postJson('/api/orders', [
		'service' => 'unrated-matches',
		'server' => 'eu',
		'account_name' => 'Offline',
		'amount' => 5,
	])->assertCreated()->assertJsonPath('client_secret', fn (string $secret): bool => str_ends_with($secret, '_secret_offline'))->json('data.id');

	$this->getJson("/api/orders/{$id}/payment")->assertJsonPath('data.offline', true);
	$this->postJson("/api/testing/orders/{$id}/pay")->assertOk()->assertJsonPath('data.status.value', 'pending');

	expect(Order::query()->findOrFail($id)->status)->toBe(OrderStatus::Pending);
});

it('only simulates payments for the customer, and only for offline orders', function (): void {
	$offline = Order::factory()->awaitingPayment()->create(['payment_intent_id' => 'pi_offline_1']);
	$stripe = Order::factory()->awaitingPayment()->create(['payment_intent_id' => 'pi_3RealStripeIntent']);

	$this->actingAs(User::factory()->customer()->create())->postJson("/api/testing/orders/{$offline->id}/pay")->assertForbidden();
	$this->actingAs($stripe->customer)->postJson("/api/testing/orders/{$stripe->id}/pay")
		->assertConflict()
		->assertJsonPath('message', 'Only offline payments can be simulated.');

	expect($stripe->fresh()?->status)->toBe(OrderStatus::AwaitingPayment);
});
