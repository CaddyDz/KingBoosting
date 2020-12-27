<?php

declare(strict_types=1);

namespace App\Nova;

use NovaIcon\Icon;
use Timothyasp\Badge\Badge;
use Illuminate\Http\Request;
use App\Nova\Actions\LockOrder;
use Laravel\Nova\Fields\{BelongsTo, ID, KeyValue, Number, Text};
class Order extends Resource
{
	/**
	 * The model the resource corresponds to.
	 *
	 * @var string
	 */
	public static $model = \App\Models\Order::class;

	/**
	 * The single value that should be used to represent the resource when being displayed.
	 *
	 * @var string
	 */
	public static $title = 'service';

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
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function fields(Request $request)
	{
		return [
			Icon::make('')
				->icon(
					fn (): string => $this->icon
				)->css(fn (): string => [
					'pending' => 'text-info',
					'progress' => 'text-warning-dark',
					'paused' => 'text-black',
					'completed' => 'text-success',
					'suspended' => 'text-danger',
				][$this->status]),
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
			ID::make(__('ID'), 'id')->sortable()->canSee(fn ($request) => $request->user()->hasRole('Admin')),
			Text::make(__('Service'), 'service'),
			Text::make(__('Tier'), 'tier'),
			Text::make(__('Division'), 'division'),
			Text::make(__('Server'), 'server'),
			Number::make(__('Wins'), 'wins'),
			BelongsTo::make(__('Booster'), 'booster', User::class),
			BelongsTo::make(__('Client'), 'client', User::class),
			Text::make(__('Status'), 'status'),
			Text::make(__('Queue'), 'queue'),
			KeyValue::make(__('Options'), 'options'),
			Text::make(__('Price'), 'price'),
		];
	}

	/**
	 * Get the cards available for the request.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function cards(Request $request)
	{
		return [];
	}

	/**
	 * Get the filters available for the resource.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function filters(Request $request)
	{
		return [];
	}

	/**
	 * Get the lenses available for the resource.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function lenses(Request $request)
	{
		return [];
	}

	/**
	 * Get the actions available for the resource.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function actions(Request $request)
	{
		return [
			(new LockOrder)->canSee(fn ($request) => $request->user()->hasRole('Booster'))
		];
	}
}
