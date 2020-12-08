<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class LockOrder extends Action
{
	use InteractsWithQueue, Queueable;

	/**
	 * Perform the action on the given models.
	 *
	 * @param  \Laravel\Nova\Fields\ActionFields  $fields
	 * @param  \Illuminate\Support\Collection  $orders
	 * @return mixed
	 */
	public function handle(ActionFields $fields, Collection $orders)
	{
		foreach ($orders as $order) {
			$order->status = 'progress';
			$order->save();
		}
	}

	/**
	 * Get the fields available on the action.
	 *
	 * @return array
	 */
	public function fields()
	{
		return [];
	}
}
