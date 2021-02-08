<?php

declare(strict_types=1);

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class OrderServiceFilter extends Filter
{
	/**
	 * The filter's component.
	 *
	 * @var string
	 */
	public $component = 'select-filter';

	/**
	 * Apply the filter to the given query.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  mixed  $value
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function apply(Request $request, $query, $value)
	{
		return $query->where('service', $value);
	}

	/**
	 * Get the filter's available options.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function options(Request $request)
	{
		return [
			"Division Boosting",
			"Ranked Win Boosting",
			"Duo Queue Boosting",
			"Placement Matches Boosting",
			"Champion Mastery",
			"Clash Boosting",
			"Normal matches",
			"Account leveling",
			"Rank Boosting",
			"Placement Matches",
			"Competitive Win Boosting",
			"Unrated Matches",
			"Ranked Win Boosting",
			"Rank Boosting",
			"Wildrift Placement Matches",
			"Normal Matches",
		];
	}
}
