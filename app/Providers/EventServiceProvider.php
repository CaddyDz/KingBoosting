<?php

declare(strict_types=1);

namespace App\Providers;

use App\Listeners\LockOrder;
use NovaButton\Events\ButtonClick;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\{Events\Registered, Listeners\SendEmailVerificationNotification};

class EventServiceProvider extends ServiceProvider
{
	/**
	 * The event listener mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		Registered::class => [
			SendEmailVerificationNotification::class,
		],
		ButtonClick::class => [
			LockOrder::class,
		],
	];

	/**
	 * Register any events for your application.
	 *
	 * @return void
	 */
	public function boot(): void
	{
		//
	}
}
