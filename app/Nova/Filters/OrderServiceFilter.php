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
			__("Division Boosting") => "Division Boosting",
			__("Ranked Win Boosting") => "Ranked Win Boosting",
			__("Duo Queue Boosting") => "Duo Queue Boosting",
			__("Placement Matches Boosting") => "Placement Matches Boosting",
			__("Champion Mastery") => "Champion Mastery",
			__("Clash Boosting") => "Clash Boosting",
			__("Normal matches") => "Normal matches",
			__("Account leveling") => "Account leveling",
			__("Rank Boosting") => "Rank Boosting",
			__("Placement Matches") => "Placement Matches",
			__("Competitive Win Boosting") => "Competitive Win Boosting",
			__("Unrated Matches") => "Unrated Matches",
			__("Ranked Win Boosting") => "Ranked Win Boosting",
			__("Rank Boosting") => "Rank Boosting",
			__("Wildrift Placement Matches") => "Wildrift Placement Matches",
			__("Normal Matches") => "Normal Matches",
		];
	}
}
