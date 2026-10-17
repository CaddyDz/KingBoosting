<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\Pricing\QuoteEngine;
use App\Models\{Order, User};
use Illuminate\Support\Facades\DB;
use App\Enums\{OrderEventType, OrderStatus};
use App\Payments\{PaymentGateway, PaymentIntent};

/**
 * Checkout: price the order on the server, save it as awaiting payment, and open a Stripe PaymentIntent
 * for that price. The Stripe webhook moves it on once the customer pays.
 */
class PlaceOrder
{
	public function __construct(private QuoteEngine $quotes, private PaymentGateway $payments) {}

	/**
	 * @param  array{service: string, server: string, account_name: string, account_login?: string|null, account_password?: string|null, comment?: string|null, booster_id?: int|null}  $details
	 * @param  array<string, mixed>  $quoteInput  the order builder's input; a price in it is never read
	 * @return array{0: Order, 1: PaymentIntent}
	 */
	public function handle(User $customer, array $details, array $quoteInput): array
	{
		$quote = $this->quotes->quote($details['service'], $quoteInput);

		$order = DB::transaction(function () use ($customer, $details, $quote): Order {
			$order = new Order([
				'game' => $quote->service->game,
				'service' => $quote->service->key,
				'title' => $quote->title,
				'server' => $details['server'],
				'account_name' => $details['account_name'],
				'account_login' => $details['account_login'] ?? null,
				'account_password' => $details['account_password'] ?? null,
				'comment' => $details['comment'] ?? null,
				'quote' => $quote->toArray(),
				'price_cents' => $quote->totalCents,
			]);
			$order->forceFill([
				'customer_id' => $customer->id,
				'status' => OrderStatus::AwaitingPayment,
				// The booster picked at checkout gets the order once it's paid (see ConfirmPayment)
				'booster_id' => $details['booster_id'] ?? null,
			])->save();

			$order->recordEvent(OrderEventType::Created, $customer, null, OrderStatus::AwaitingPayment);

			return $order;
		});

		// Outside the transaction: if Stripe fails, the unpaid order stays and the cleanup removes it later
		$intent = $this->payments->createPaymentIntent($order);
		$order->forceFill(['payment_intent_id' => $intent->id])->save();

		return [$order, $intent];
	}
}
