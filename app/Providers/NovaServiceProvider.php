<?php

declare(strict_types=1);

namespace App\Providers;

use App\Nova\Dashboards\Rules;
use Illuminate\Support\Facades\{Gate, Route};
use App\Http\Controllers\Nova\LoginController;
use Anaseqal\NovaSidebarIcons\NovaSidebarIcons;
use Laravel\Nova\{Nova, NovaApplicationServiceProvider};
use App\Nova\{Application, Booster, Coupon, Fine, Gift, Order, User};
use Vyuldashev\NovaPermission\{NovaPermissionTool, Permission, Role};
use DigitalCreative\CollapsibleResourceManager\CollapsibleResourceManager;
use DigitalCreative\CollapsibleResourceManager\Resources\{InternalLink, NovaResource, TopLevelResource};
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
		Nova::script('menuFix', resource_path('js/fixMenu.js'));
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
		return [
			new Rules()
		];
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
			new CollapsibleResourceManager([
				'navigation' => [
					TopLevelResource::make([
						'label' => 'Orders',
						'icon' => Order::icon(),
						'linkTo' => Order::class,
					]),
					TopLevelResource::make([
						'label' => 'Profile',
						'icon' => User::icon(),
						'linkTo' => NovaResource::make(User::class)->detail(auth()->id()),
					]),
					TopLevelResource::make([
						'label' => 'Rules',
						'icon' => view('nova::svg.icon-rules')->render(),
						'linkTo' => InternalLink::make([
							'target' => '_self',
							'path' => '/dashboards/rules',
						]),
					]),
					TopLevelResource::make([
						'label' => 'Admin',
						'expanded' => false,
						'icon' => view('nova::svg.icon-dashboard')->render(),
						'resources' => [
							User::class,
							Booster::class,
							Fine::class,
							Application::class,
							Gift::class,
							Coupon::class,
							Role::class,
							Permission::class,
						]
					]),
				],
			]),
			new \Bolechen\NovaActivitylog\NovaActivitylog(),
			NovaPermissionTool::make(),
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
