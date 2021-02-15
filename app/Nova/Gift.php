<?php

declare(strict_types=1);

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\{BelongsTo, Boolean, ID, Number};

class Gift extends Resource
{
	/**
	 * The model the resource corresponds to.
	 *
	 * @var string
	 */
	public static $model = \App\Models\Gift::class;

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
		'id', 'amount', 'paid'
	];

	/**
	 * The relationship columns that should be searched.
	 *
	 * @var array
	 */
	public static $searchRelations = [
		'booster' => ['first_name', 'last_name', 'username'],
		'order' => [
			'purchase',
			'service',
			'server',
			'summoner',
			'comment',
		],
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
			ID::make(__('ID'), 'id')->sortable(),
			BelongsTo::make(__('Booster'), 'booster', User::class),
			BelongsTo::make(__('Order'), 'order', Order::class),
			Number::make(__('Amount'), 'amount'),
			Boolean::make(__('Paid'), 'paid')
		];
	}
}
