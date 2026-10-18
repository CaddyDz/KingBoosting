<?php

declare(strict_types=1);

namespace App\Http\Controllers\Stripe;

use Stripe\Webhook;
use App\Models\Order;
use UnexpectedValueException;
use Stripe\{Event, PaymentIntent};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, Response};
use Illuminate\Support\Facades\{DB, Log};
use Stripe\Exception\SignatureVerificationException;
use Illuminate\Database\UniqueConstraintViolationException;
use App\Actions\Orders\{ConfirmPayment, RecordPaymentFailure, RefundMismatchedPayment};

class WebhookController extends Controller
{
	public function __construct(
		private ConfirmPayment $confirm,
		private RecordPaymentFailure $recordFailure,
		private RefundMismatchedPayment $refundMismatch,
	) {}

	/**
	 * Stripe's events for the PaymentIntents created at checkout. The signature is checked against
	 * STRIPE_WEBHOOK_SECRET, and each event id is handled once, however often Stripe sends it.
	 */
	public function __invoke(Request $request): Response
	{
		try {
			$event = Webhook::constructEvent(
				$request->getContent(),
				(string) $request->header('Stripe-Signature'),
				(string) config('services.stripe.webhook_secret'),
			);
		} catch (SignatureVerificationException|UnexpectedValueException) {
			return response('Invalid signature', 400);
		}

		try {
			// The event id and its effects commit together: if handling fails, Stripe's retry runs it again
			return DB::transaction(function () use ($event): Response {
				DB::table('stripe_events')->insert(['id' => $event->id, 'type' => $event->type, 'processed_at' => now()]);

				return $this->handle($event);
			});
		} catch (UniqueConstraintViolationException) {
			return response('Already processed');
		}
	}

	private function handle(Event $event): Response
	{
		$intent = $event->data->object;
		if (! $intent instanceof PaymentIntent || ! in_array($event->type, ['payment_intent.succeeded', 'payment_intent.payment_failed'], true)) {
			return response('Ignored');
		}

		$order = Order::query()->where('payment_intent_id', $intent->id)->first();
		if ($order === null) {
			Log::warning('Stripe webhook for an unknown PaymentIntent', ['event' => $event->id, 'payment_intent' => $intent->id]);

			return response('Unknown payment intent');
		}

		if ($event->type === 'payment_intent.payment_failed') {
			// `message` is optional in Stripe's error object
			$error = $intent->last_payment_error;
			$this->recordFailure->handle($order, isset($error->message) ? (string) $error->message : null);

			return response('OK');
		}

		// Never trust a payment that doesn't match the order's server-side price. If the refund fails, the
		// transaction rolls back and Stripe's retry tries it again.
		if ($intent->amount_received !== $order->price_cents || $intent->currency !== 'eur') {
			$this->refundMismatch->handle($order, (int) $intent->amount_received, (string) $intent->currency);

			return response('Amount mismatch, refunded');
		}

		$this->confirm->handle($order);

		return response('OK');
	}
}
