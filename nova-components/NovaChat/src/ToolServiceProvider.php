<?php

declare(strict_types=1);

namespace Sitando\NovaChat;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ToolServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->app->booted(function () {
			$this->routes();
		});

		Nova::serving(function (ServingNova $event) {
			Nova::script('nova-chat', __DIR__ . '/../dist/js/tool.js');
			Nova::style('nova-chat', __DIR__ . '/../dist/css/tool.css');
		});
	}

	/**
	 * Register the tool's routes.
	 *
	 * @return void
	 */
	protected function routes()
	{
		if ($this->app->routesAreCached()) {
			return;
		}

		Route::middleware(['nova'])
			->prefix('nova-vendor/nova-chat')
			->group(__DIR__ . '/../routes/api.php');
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}
}
