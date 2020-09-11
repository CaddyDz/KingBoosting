<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsToMany;

class Champion extends Resource
{
	/**
	 * The model the resource corresponds to.
	 *
	 * @var string
	 */
	public static $model = 'App\Champion';

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
		'name', 'identifier', 'key', 'tags', 'roles'
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
			Number::make('Key')->required()->sortable(),
			Text::make('Name')->required()->sortable(),
			Text::make('Identifier')->sortable(),
			Image::make('Image'),
			BelongsToMany::make(__('Roles'), 'roles', '\App\Nova\ChampionRole')->searchable(),
			BelongsToMany::make(__('Tags'), 'tags', '\App\Nova\ChampionTag')->searchable(),
		];
	}
}
