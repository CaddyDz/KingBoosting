<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Nova\Metrics\MyOrders;
use Laravel\Nova\Fields\Status;
use App\Nova\Metrics\PendingOrders;
use App\Nova\Metrics\WatchedOrders;
use Vyuldashev\NovaMoneyField\Money;
use Laravel\Nova\Http\Requests\NovaRequest;
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
            Status::make('Status'),
            ID::make()->sortable(),
            Money::make('Price')
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
            new LensesMyOrders,
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
