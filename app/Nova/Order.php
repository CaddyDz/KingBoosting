<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use App\Nova\Metrics\MyOrders;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Lenses\MyOrders as LensesMyOrders;
use App\Nova\Lenses\PendingOrders as LensesPendingOrders;
use App\Nova\Metrics\PendingOrders;

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
            ID::make()->sortable(),
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
