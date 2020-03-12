<?php

namespace App\Nova\Lenses;

use NovaIcon\Icon;
use Laravel\Nova\Fields\ID;
use Timothyasp\Badge\Badge;
use Illuminate\Http\Request;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Vyuldashev\NovaMoneyField\Money;
use Laravel\Nova\Http\Requests\LensRequest;

class WatchedOrders extends Lens
{
    /**
     * Get the query builder / paginator for the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\LensRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public static function query(LensRequest $request, $query)
    {
        return $request->withOrdering($request->withFilters(
            $query
        ));
    }

    /**
     * Get the fields available to the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Icon::make('')->icon(fn () => optional($this->resource)->icon ?? 'entypo:circular-graph')->css(function () {
                $options = [
                    'pending' => 'text-info',
                    'progress' => 'text-warning-dark',
                    'paused' => 'text-black',
                    'completed' => 'text-success',
                    'suspended'   => 'text-danger',
                ];

                return $options[optional($this->resource)->status ?? 'paused'];
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
            Text::make('Purchase', fn (): string => 'Something weird'),
            Money::make('Price'),
            DateTime::make('Creation', fn (): string => $this->resource->created_at),
            Text::make('Employee', fn () => optional($this->resource->booster)->username),
        ];
    }

    /**
     * Get the cards available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return parent::actions($request);
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'watched-orders';
    }
}
