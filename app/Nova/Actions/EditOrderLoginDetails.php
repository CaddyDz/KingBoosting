<?php

declare(strict_types=1);

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Nova\Fields\{ActionFields, Text};

class EditOrderLoginDetails extends Action
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
			$order->riot_login = $fields->riot_login;
			$order->riot_password = $fields->riot_password;
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
		return [
			Text::make(__('Login name'), 'riot_login'),
			Text::make(__('Login password'), 'riot_password'),
		];
	}
}
