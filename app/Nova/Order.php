<?php

declare(strict_types=1);

namespace App\Nova;

use NovaIcon\Icon;
use Timothyasp\Badge\Badge;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use App\Nova\Metrics\MyOrders;
use Laravel\Nova\Fields\DateTime;
use App\Nova\Metrics\PendingOrders;
use App\Nova\Metrics\WatchedOrders;
use Vyuldashev\NovaMoneyField\Money;
use App\Nova\Lenses\MyOrders as LensesMyOrders;
use App\Nova\Lenses\PendingOrders as LensesPendingOrders;
use App\Nova\Lenses\WatchedOrders as LensesWatchedOrders;

class Order extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Order';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Icon::make('')
                ->icon(function () {
                    return $this->icon;
                })->css(function () {
                    $options = [
                        'pending' => 'text-info',
                        'progress'   => 'text-warning-dark',
                        'paused'   => 'text-black',
                        'completed'   => 'text-success',
                        'suspended'   => 'text-danger',
                    ];

                    return $options[$this->status];
                }),
            Badge::make('Status')
                ->options([
                    'pending' => __('Awaiting for booster'),
                    'progress' => __('In Progress'),
                    'paused' => __('Paused'),
                    'completed' => __('Complete'),
                    'suspended' => __('Suspended'),
                ])
                ->colors([
                    'pending' => '#64cedb',
                    'progress' => '#d68842',
                    'paused' => '#000',
                    'completed' => '#42d6a9',
                    'suspended' => '#ca404d',
                ])->displayUsingLabels(),
            ID::make()->sortable(),
            Text::make('Purchase', function () {
                return "{$this->service->name} <br>" . country_flag($this->client->country);
            })->asHtml(),
            Money::make('Price'),
            DateTime::make('Creation', function () {
                return $this->created_at;
            }),
            Text::make('Employee', function () {
                return optional($this->booster)->username;
            })
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [
            (new MyOrders)->route(
                'lens',
                ['resourceName' => 'orders', 'lens' => 'my-orders']
            ),
            (new PendingOrders)->route(
                'lens',
                ['resourceName' => 'orders', 'lens' => 'pending-orders']
            ),
            (new WatchedOrders)->route(
                'lens',
                ['resourceName' => 'orders', 'lens' => 'watched-orders']
            ),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [
            new LensesMyOrders(static::newModel()),
            new LensesPendingOrders,
            new LensesWatchedOrders,
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    /**
     * The icon of the resource.
     *
     * @return string
     */
    public static function icon()
    {
        return view('nova::svg.icon-order')->render();
    }
}
