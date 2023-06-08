<?php

declare(strict_types=1);

namespace App\Nova\Actions;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Fields\{ActionFields, Select};

class AssignOrder extends Action implements ShouldQueue
{
	use InteractsWithQueue, Queueable;

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
			$order->booster_id = $fields->booster;
			$order->status = 'progress';
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
		$boosters = User::role('Booster')->pluck('username', 'id');
		return [
			Select::make(__('Booster'), 'booster')
				->options($boosters)
				->displayUsingLabels()
				->searchable(),
		];
	}
}
