<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use Throwable;
use App\Models\Order;
use App\Enums\OrderStatus;
use App\Payments\PaymentGateway;
use Illuminate\Support\Facades\Schema;

/**
 * Before demo:reset wipes the database: close every unpaid order's PaymentIntent, so a payment page left open
 * can't take money for an order that's gone. Stripe being down doesn't stop the reset.
 */
class CloseOpenCheckouts
{
	public function __construct(private PaymentGateway $payments) {}

	/**
	 * @return list<string> why each checkout that's still open couldn't be closed
	 */
	public function handle(): array
	{
		// A database that was never migrated has nothing to close
		if (! Schema::hasTable('orders')) {
			return [];
		}

		$problems = [];
		Order::query()
			->where('status', OrderStatus::AwaitingPayment)
			->whereNotNull('payment_intent_id')
			->lazyById()
			->each(function (Order $order) use (&$problems): void {
				try {
					$this->payments->cancelPaymentIntent($order);
				} catch (Throwable $exception) {
					report($exception);
					$problems[] = "Order #{$order->id}'s payment couldn't be closed: {$exception->getMessage()}";
				}
			});

		return $problems;
	}
}
