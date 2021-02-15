<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot(): void
	{
		// Disable mass assignment protection on all models
		Model::unguard();
		if (!app()->isLocal()) {
			\URL::forceScheme('https');
		}
	}
}
