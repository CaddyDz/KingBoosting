<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Heading;

class Tier extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Tier';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
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
            Text::make('Name'),
            Code::make('Background Color', 'box_shadow')->language('sass'),
            Heading::make('<div style="width: 100%;height: 150px;box-shadow: ' . $this->box_shadow . '"></div>')->asHtml(),
            Image::make('Image')->thumbnail(function ($value) {
                return $value;
            })->preview(function ($value) {
                return $value;
            })->hideFromIndex(function () {
                return $this->model()->divisions->isNotEmpty();
            })->hideFromDetail(function () {
                return $this->model()->divisions->isNotEmpty();
            })->hideWhenCreating(function () {
                return $this->model()->divisions->isNotEmpty();
            })->hideWhenUpdating(function () {
                return $this->model()->divisions->isNotEmpty();
            }),
            Number::make('Price')->min(1)->max(1000)->step(0.01)->hideFromIndex(function () {
                return $this->model()->divisions->isNotEmpty();
            })->hideFromDetail(function () {
                return $this->model()->divisions->isNotEmpty();
            })->hideWhenCreating(function () {
                return $this->model()->divisions->isNotEmpty();
            })->hideWhenUpdating(function () {
                return $this->model()->divisions->isNotEmpty();
            }),
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
        return [];
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
        return [];
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
}
