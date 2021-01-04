<?php

declare(strict_types=1);

namespace App\Providers;

use Anaseqal\NovaSidebarIcons\NovaSidebarIcons;
use Illuminate\Support\Facades\{Gate, Route};
use App\Http\Controllers\Nova\LoginController;
use Laravel\Nova\{Nova, NovaApplicationServiceProvider};
use EricLagarda\NovaLinkResource\NovaLinkResource;


class NovaServiceProvider extends NovaApplicationServiceProvider
{
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot(): void
	{
		Nova::serving(fn () => Nova::style('theme', resource_path('css/theme.css')));
		Nova::sortResourcesBy(fn ($resource) => $resource::$priority ?? 9999);
		parent::boot();
	}

	/**
	 * Register the Nova routes.
	 *
	 * @return void
	 */
	protected function routes(): void
	{
		$this->withAuthenticationRoutes();
		Nova::routes()
			->withPasswordResetRoutes()
			->register();
	}

	/**
	 * Register the Nova authentication routes.
	 *
	 * @param array $middleware
	 * @return $this
	 */
	public function withAuthenticationRoutes($middleware = ['web'])
	{
		Route::namespace('App\Http\Controllers')
			->domain(config('nova.domain', null))
			->middleware($middleware)
			->prefix(Nova::path())
			->group(function () {
				Route::get('/login', [LoginController::class, 'showLoginForm']);
				Route::post('/login', [LoginController::class, 'login'])->name('nova.login');
			});

		Route::namespace('Laravel\Nova\Http\Controllers')
			->domain(config('nova.domain', null))
			->middleware(config('nova.middleware', []))
			->prefix(Nova::path())
			->group(function () {
				Route::get('/logout', [LoginController::class, 'logout'])->name('nova.logout');
			});

		return $this;
	}

	/**
	 * Register the Nova gate.
	 *
	 * This gate determines who can access Nova in non-local environments.
	 *
	 * @return void
	 */
	protected function gate(): void
	{
		Gate::define('viewNova', fn () => true);
	}

	/**
	 * Get the cards that should be displayed on the default Nova dashboard.
	 *
	 * @return array
	 */
	protected function cards(): array
	{
		return [];
	}

	/**
	 * Get the extra dashboards that should be displayed on the Nova dashboard.
	 *
	 * @return array
	 */
	protected function dashboards(): array
	{
		return [];
	}

	/**
	 * Get the tools that should be listed in the Nova sidebar.
	 *
	 * @return array
	 */
	public function tools(): array
	{
		return [
			new NovaSidebarIcons,
			(new NovaLinkResource())->name('Profile')
				->to('/resources/users/' . auth()->id())
				->icon('<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24" width="24" height="24"><path fill="var(--sidebar-icon)" d="M12 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm9 11a1 1 0 0 1-2 0v-2a3 3 0 0 0-3-3H8a3 3 0 0 0-3 3v2a1 1 0 0 1-2 0v-2a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v2z"/></svg>'),

		];
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register(): void
	{
		//
	}
}
