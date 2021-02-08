<?php

declare(strict_types=1);

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PauseOrder extends Action implements ShouldQueue
{
	use InteractsWithQueue, Queueable;

	/**
	 * Get the displayable name of the action.
	 *
	 * @return string
	 */
	public function name(): string
	{
		return __('Pause Order');
	}

	/**
	 * Perform the action on the given orders.
	 *
	 * @param \Laravel\Nova\Fields\ActionFields $fields
	 * @param \Illuminate\Support\Collection $orders
	 * @return mixed
	 */
	public function handle(ActionFields $fields, Collection $orders)
	{
		foreach ($orders as $order) {
			$order->status = 'paused';
			$order->save();
		}
	}

	/**
	 * Get the fields available on the action.
	 *
	 * @return array
	 */
	public function fields(): array
	{
		return [];
	}
}
