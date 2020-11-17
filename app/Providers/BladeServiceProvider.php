<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\{Facades\Blade, ServiceProvider};

class BladeServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap services.
	 *
	 * Register a blade global directive
	 * available to all views
	 * determines app environment
	 * use it to load assets or cdn based on env
	 *
	 * @return void
	 */
	public function boot(): void
	{
		Blade::if('env', fn ($env) => app()->environment($env));
	}
}
