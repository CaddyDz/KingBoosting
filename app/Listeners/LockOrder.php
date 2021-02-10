<?php

declare(strict_types=1);

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LockOrder implements ShouldQueue
{
	use InteractsWithQueue;

	/**
	 * Handle the event.
	 *
	 * @param object $event
	 * @return void
	 */
	public function handle($event): void
	{
		if ($event->key == 'lock-order') {
			$event->resource->booster_id = auth()->id();
			$event->resource->status = 'progress';
			$event->resource->save();
		}
	}
}
