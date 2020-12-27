<?php

declare(strict_types=1);

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\{ID, Text};
class Application extends Resource
{
	/**
	 * The model the resource corresponds to.
	 *
	 * @var string
	 */
	public static $model = \App\Models\Application::class;

	/**
	 * The single value that should be used to represent the resource when being displayed.
	 *
	 * @var string
	 */
	public static $title = 'summoner';

	/**
	 * The columns that should be searched.
	 *
	 * @var array
	 */
	public static $search = [
		'id', 'summoner', 'application', 'languages', 'skype',
	];

	/**
	 * Get the fields displayed by the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function fields(Request $request): array
	{
		return [
			ID::make(__('ID'), 'id')->sortable(),
			Text::make(__('Summoner Name'), 'summoner')->sortable(),
			Text::make(__('Applicationn'), 'application')->sortable(),
			Text::make(__('Email'), 'email')->hideFromIndex()->sortable(),
			Text::make(__('Country'), 'country')->hideFromIndex()->sortable(),
			Text::make(__('Skype'), 'skype')->sortable(),
			Text::make(__('Rank'), 'rank')->hideFromIndex()->sortable(),
			Text::make(__('Languages'), 'languages')->sortable(),
			Text::make(__('Games'), 'games')->hideFromIndex()->sortable(),
			Text::make(__('Servers'), 'servers')->hideFromIndex()->sortable(),
			Text::make(__('Hours'), 'hours')->hideFromIndex()->sortable(),
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
