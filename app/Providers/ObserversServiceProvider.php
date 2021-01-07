<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\{Order, User};
use Illuminate\Support\ServiceProvider;
use App\Observers\{OrderObserver, UserObserver};

class ObserversServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot(): void
	{
		User::observe(UserObserver::class);
		Order::observe(OrderObserver::class);
	}
}
