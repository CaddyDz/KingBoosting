<?php

declare(strict_types=1);

namespace App\Nova\Dashboards;

use IDF\HtmlCard\HtmlCard;
use Laravel\Nova\Dashboard;

class Rules extends Dashboard
{
	/**
	 * Get the displayable name of the dashboard.
	 *
	 * @return string
	 */
	public static function label()
	{
		return 'Rules';
	}

	/**
	 * Get the cards for the dashboard.
	 *
	 * @return array
	 */
	public function cards()
	{
		return [
			(new HtmlCard())->width('full')->view('rules'),
		];
	}

	/**
	 * Get the URI key for the dashboard.
	 *
	 * @return string
	 */
	public static function uriKey()
	{
		return 'rules';
	}
}
