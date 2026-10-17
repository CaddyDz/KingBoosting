<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use Closure;
use LogicException;
use App\Models\{Order, User};
use Illuminate\Support\Facades\{DB, Gate};
use App\Enums\OrderEventType;

/**
 * Runs one order action: lock the row, check the policy against it, apply the change and record the event,
 * all in one transaction.
 */
class OrderTransition
{
	/**
	 * @param  Closure(Order): array<string, mixed>  $changes  the attributes to set on the locked order
	 * @param  (Closure(Order): array<string, mixed>)|null  $meta  the event's details, read from the locked order before the change
	 */
	public function run(User $actor, Order $order, string $ability, OrderEventType $type, Closure $changes, ?Closure $meta = null): Order
	{
		return DB::transaction(function () use ($actor, $order, $ability, $type, $changes, $meta): Order {
			$locked = Order::query()->lockForUpdate()->findOrFail($order->getKey());

			Gate::forUser($actor)->authorize($ability, $locked);

			$from = $locked->status;
			$details = $meta ? $meta($locked) : [];
			$locked->forceFill($changes($locked))->save();

			if ($locked->status !== $from && ! $from->canTransitionTo($locked->status)) {
				throw new LogicException("The {$ability} action moved an order from {$from->value} to {$locked->status->value}.");
			}

			$locked->recordEvent($type, $actor, $from, $locked->status, $details);

			return $locked;
		});
	}

}
