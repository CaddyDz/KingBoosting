<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\User;
use App\Enums\OrderEventType;
use App\Actions\Chat\PostMessage;
use App\Events\OrderEventRecorded;

/**
 * Every order action leaves a line in the order's chat, so both sides see what happened and when.
 */
class PostSystemMessage
{
	public function __construct(private PostMessage $postMessage) {}

	public function handle(OrderEventRecorded $recorded): void
	{
		$event = $recorded->event->loadMissing(['order.booster', 'actor']);
		$order = $event->order;
		/** @var User|null $holder set on the order, if any */
		$holder = $order->booster;
		$booster = $holder->username ?? 'The booster';
		$actor = $this->name($event->actor, $order->customer_id);

		$body = match ($event->type) {
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
			// Before payment there's no chat, and payouts are between the booster and King Boosting
			OrderEventType::Created, OrderEventType::PaymentFailed, OrderEventType::PaidOut => null,
		};

		if ($body !== null) {
			$this->postMessage->handle(null, $order, $body);
		}
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
