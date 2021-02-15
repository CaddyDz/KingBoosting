<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Server;

class ServerObserver
{
	/**
	 * Handle the Server "created" event.
	 *
	 * @param  \App\Models\Server  $server
	 * @return void
	 */
	public function created(Server $server)
	{
		//
	}

	/**
	 * Handle the Server "updated" event.
	 *
	 * @param  \App\Models\Server  $server
	 * @return void
	 */
	public function updated(Server $server)
	{
		//
	}

	/**
	 * Handle the Server "deleted" event.
	 *
	 * @param  \App\Models\Server  $server
	 * @return void
	 */
	public function deleted(Server $server)
	{
		//
	}

	/**
	 * Handle the Server "restored" event.
	 *
	 * @param  \App\Models\Server  $server
	 * @return void
	 */
	public function restored(Server $server)
	{
		//
	}

	/**
	 * Handle the Server "force deleted" event.
	 *
	 * @param  \App\Models\Server  $server
	 * @return void
	 */
	public function forceDeleted(Server $server)
	{
		//
	}
}
