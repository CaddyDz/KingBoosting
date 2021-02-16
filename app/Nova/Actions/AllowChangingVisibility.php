<?php

declare(strict_types=1);

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AllowChangingVisibility extends Action implements ShouldQueue
{
	use InteractsWithQueue, Queueable;

	/**
	 * Perform the action on the given boosters.
	 *
	 * @param \Laravel\Nova\Fields\ActionFields $fields
	 * @param \Illuminate\Support\Collection $boosters
	 * @return mixed
	 */
	public function handle(ActionFields $fields, Collection $boosters)
	{
		foreach ($boosters as $booster) {
			// Grant the permission
			$booster->givePermissionTo('Change visibility');
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
