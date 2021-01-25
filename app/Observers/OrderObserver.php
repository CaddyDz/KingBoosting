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
}
