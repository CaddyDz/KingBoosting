<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
	/**
	 * Handle the Order "creating" event.
	 *
	 * @param \App\Models\Order $order
	 * @return void
	 */
	public function creating(Order $order): void
	{
		if (auth()->check()) {
			$order->client_id = auth()->id();
		}
		// If a booster has been selected for the order, set its status to progress, otherwise pending
		(bool) $order->booster_id ? $order->status = 'progress' : 'pending';
	}

	/**
	 * Handle the Order "created" event.
	 *
	 * @param \App\Models\Order $order
	 * @return void
	 */
	public function created(Order $order): void
	{
		$order->chat()->create();
	}

	/**
	 * Handle the Order "updating" event.
	 *
	 * @param \App\Models\Order $order
	 * @return void
	 */
	public function updating(Order $order): void
	{
		if ($order->status == 'pending') {
			$order->booster_id = null;
		}
	}
}
