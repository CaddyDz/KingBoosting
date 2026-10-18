<?php

declare(strict_types=1);

use App\Models\Order;
use Tests\Fakes\FakePaymentGateway;
use App\Actions\Orders\CloseOpenCheckouts;
use Illuminate\Support\Facades\Exceptions;
use App\Payments\{PaymentAlreadyTaken, PaymentGateway};

beforeEach(function (): void {
	$this->app->instance(PaymentGateway::class, $this->payments = new FakePaymentGateway);
});

it('closes the PaymentIntent of every unpaid order, and only those', function (): void {
	$unpaid = Order::factory()->awaitingPayment()->count(2)->create();
	Order::factory()->awaitingPayment()->create(['payment_intent_id' => null]);
	Order::factory()->pending()->create();

	expect(app(CloseOpenCheckouts::class)->handle())->toBe([])
		->and($this->payments->cancelled)->toEqualCanonicalizing($unpaid->pluck('payment_intent_id')->all());
});

it('closes the rest and reports the checkouts it couldn’t close', function (): void {
	Exceptions::fake();
	[$paid, $open] = Order::factory()->awaitingPayment()->count(2)->create();
	$this->payments->taken[] = $paid->payment_intent_id;

	$problems = app(CloseOpenCheckouts::class)->handle();

	expect($problems)->toBe(["Order #{$paid->id}'s payment couldn't be closed: {$paid->payment_intent_id} succeeded"])
		->and($this->payments->cancelled)->toBe([$open->payment_intent_id]);
	Exceptions::assertReported(PaymentAlreadyTaken::class);
});
