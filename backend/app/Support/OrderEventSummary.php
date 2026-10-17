<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\{OrderEvent, User};
use App\Enums\OrderEventType;

/**
 * One line saying what happened to an order, for its chat and for notifications.
 * Staff appear as "Support" and the customer as "The customer".
 */
class OrderEventSummary
{
	/**
	 * Null for events nobody needs to read about: before payment, failures (the customer gets their own
	 * notification), and payouts.
	 */
	public function of(OrderEvent $event): ?string
	{
		$event->loadMissing(['order.booster', 'actor']);
		$order = $event->order;
		/** @var User|null $holder */
		$holder = $order->booster;
		$booster = $holder->username ?? 'The booster';
		$actor = $this->name($event->actor, $order->customer_id);

		return match ($event->type) {
			OrderEventType::PaymentSucceeded => $order->booster_id !== null
				? "Payment received. {$booster} has the order and will start soon."
				: 'Payment received. The order is waiting for a booster.',
			OrderEventType::Claimed => "{$booster} claimed the order.",
			OrderEventType::Assigned => "Support gave the order to {$booster}.",
			OrderEventType::Paused => "{$actor} paused the order.",
			OrderEventType::Resumed => "{$actor} resumed the order.",
			OrderEventType::Released => "{$actor} released the order. It's back on the jobs board.",
			OrderEventType::Completed => "{$booster} completed the order.",
			OrderEventType::CredentialsUpdated => 'The customer updated the account login.',
			OrderEventType::Created, OrderEventType::PaymentFailed, OrderEventType::PaidOut => null,
		};
	}

	private function name(?User $actor, int $customerId): string
	{
		return match (true) {
			$actor === null => 'King Boosting',
			$actor->isStaff() => 'Support',
			$actor->id === $customerId => 'The customer',
			default => $actor->username,
		};
	}
}
