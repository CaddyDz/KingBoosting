<?php

declare(strict_types=1);

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class OrderFilter extends Filter
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
		// If nothing then return all orders
		if (!$value) {
			return $query;
		}
		return $query->where('status', $value);
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
			'Orders' => null,
			'Pending Orders' => 'pending',
		];
	}
}
