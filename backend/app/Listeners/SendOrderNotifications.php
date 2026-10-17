<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\{OrderEvent, User};
use App\Enums\OrderEventType;
use Illuminate\Support\Collection;
use App\Support\OrderEventSummary;
use App\Events\OrderEventRecorded;
use Illuminate\Support\Facades\Notification;
use App\Notifications\{NewOrderAvailable, OrderAssigned, OrderStatusChanged, PaymentFailed};

/**
 * Who hears about what happened to an order:
 * - every booster, when a paid order lands on the jobs board;
 * - the booster, when an order is given to them (picked at checkout or assigned by staff);
 * - the customer and the booster, when the status changes, except whoever made the change;
 * - the customer, when their payment fails.
 */
class SendOrderNotifications
{
	public function __construct(private OrderEventSummary $summary) {}

	public function handle(OrderEventRecorded $recorded): void
	{
		$event = $recorded->event->loadMissing(['order.customer', 'order.booster', 'actor']);
		$order = $event->order;
		$given = in_array($event->type, [OrderEventType::PaymentSucceeded, OrderEventType::Assigned], true) && $order->booster !== null;

		match (true) {
			$given => $order->booster->notify(new OrderAssigned($order)),
			$event->type === OrderEventType::PaymentSucceeded => Notification::send(
				User::query()->where('role', 'booster')->get(),
				new NewOrderAvailable($order),
			),
			$event->type === OrderEventType::PaymentFailed => $order->customer->notify(
				new PaymentFailed($order, (string) ($event->meta['reason'] ?? 'The payment was declined.')),
			),
			default => null,
		};

		$summary = $this->summary->of($event);
		if ($summary !== null) {
			// The booster who was just given the order already has their own notification
			Notification::send($this->parties($event)->reject(fn (User $user): bool => $given && $user->is($order->booster)), new OrderStatusChanged($order, $summary));
		}
	}

	/**
	 * The customer and the booster on the order (and the one who was on it, after a release), minus the actor.
	 *
	 * @return Collection<int, User>
	 */
	private function parties(OrderEvent $event): Collection
	{
		$order = $event->order;
		$previousBooster = $event->type === OrderEventType::Released && isset($event->meta['booster_id'])
			? User::query()->find((int) $event->meta['booster_id'])
			: null;

		return collect([$order->customer, $order->booster, $previousBooster])
			->filter()
			->unique('id')
			->reject(fn (User $user): bool => $event->actor !== null && $user->is($event->actor))
			->values();
	}
}
