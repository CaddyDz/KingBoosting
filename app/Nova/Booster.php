<?php

declare(strict_types=1);

namespace App\Nova;

use Laravel\Nova\Http\Requests\NovaRequest;

class Booster extends User
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
		return $query->role('Booster');
	}
}
