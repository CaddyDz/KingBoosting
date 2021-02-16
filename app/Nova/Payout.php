<?php

declare(strict_types=1);

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\{ID, Text};
use Laravel\Nova\Http\Requests\NovaRequest;

class Payout extends Order
{

	/**
	 * Build an "index" query for the given resource.
	 *
	 * @param \Laravel\Nova\Http\Requests\NovaRequest $request
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public static function indexQuery(NovaRequest $request, $query)
	{
		return $query->where('status', 'completed');
	}

	/**
	 * Build a "relatable" query for the given resource.
	 *
	 * This query determines which instances of the model may be attached to other resources.
	 *
	 * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public static function relatableQuery(NovaRequest $request, $query)
	{
		return parent::relatableQuery($request, $query->where('status', 'completed')->where('booster_id', auth()->id()));
	}

	/**
	 * Get the fields displayed by the resource.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function fields(Request $request)
	{
		return [
			ID::make(__('ID'), 'id')->sortable()->hideFromDetail(),
			Text::make(__('Purchase'), fn (): string => $this->purchase . '</br>' . country_flag($this->client->country) . ' ' . $this->service)->asHtml()->onlyOnIndex(),
			Text::make(__('Price'), fn () => $this->price * $this->share / 100)
				->displayUsing(fn ($price) => '$' . $price)
				->hideFromDetail()
				->canSee(fn ($request) => $request->user()->hasRole('Booster')),
		];
	}

	/**
	 * Get the actions available for the resource.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function actions(Request $request): array
	{
		return [];
	}
}
