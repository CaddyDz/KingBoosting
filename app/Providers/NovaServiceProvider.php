<?php

namespace App\Providers;

use App\Nova\Order;
use Laravel\Nova\Nova;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Events\ServingNova;
use Anaseqal\NovaSidebarIcons\NovaSidebarIcons;
use Laravel\Nova\NovaApplicationServiceProvider;
use Zoxta\NovaCloudflareCard\NovaCloudflareCard;
use Vyuldashev\NovaPermission\NovaPermissionTool;
use DigitalCreative\CollapsibleResourceManager\Resources\TopLevelResource;
use DigitalCreative\CollapsibleResourceManager\CollapsibleResourceManager;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('menuFix', __DIR__ . '/../../resources/js/fixMenu.js');
        Nova::serving(function (ServingNova $event) {
            Nova::style('blueflix', __DIR__ . '/../../resources/css/theme.css');
        });
        parent::boot();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return $user->hasPermissionTo('Access Members Area');
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            new NovaCloudflareCard(),
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            new NovaSidebarIcons,
            NovaPermissionTool::make(),
            new CollapsibleResourceManager([
                'navigation' => [
                    TopLevelResource::make([
                        // 'label' => 'Resources',
                        'resources' => [
                            Order::class,
                        ]
                    ]),
                ]
            ])
        ];
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
