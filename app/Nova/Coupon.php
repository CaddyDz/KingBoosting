<?php

declare(strict_types=1);

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\{Boolean, ID, Number, Select, Text};

class Coupon extends Resource
{
	/**
	 * The model the resource corresponds to.
	 *
	 * @var string
	 */
	public static $model = \App\Models\Coupon::class;

	/**
	 * The single value that should be used to represent the resource when being displayed.
	 *
	 * @var string
	 */
	public static $title = 'code';

	/**
	 * The columns that should be searched.
	 *
	 * @var array
	 */
	public static $search = [
		'id', 'code', 'amount', 'type'
	];

	/**
	 * Get a fresh instance of the model represented by the resource.
	 */
	public static function newModel()
	{
		$model = static::$model;
		$coupon = new $model();
		// Set the dafault value for the reception date
		$coupon->code = strtoupper(Str::random(10));
		$coupon->amount = 5;
		$coupon->expiration = now()->addMonths(3);
		$coupon->used = false;
		$coupon->type = 'Eloboost';
		$coupon->public = false;
		return $coupon;
	}

	/**
	 * Get the fields displayed by the resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function fields(Request $request)
	{
		return [
			ID::make(__('ID'), 'id')->sortable(),
			Text::make(__('Code'), 'code')->required(),
			Number::make(__('Amount'), 'amount')->required(),
			Select::make(__('Type'), 'type')
				->options([
					'Smurf' => 'Smurf',
					'Eloboost' => 'Eloboost',
					'Eloboost & Smurf' => 'Eloboost & Smurf',
					'Agent' => 'Agent',
					'Affilate' => 'Affilate',
				])->displayUsingLabels(),
			Boolean::make(__('Used'), 'used'),
			Boolean::make(__('Public'), 'public'),
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
