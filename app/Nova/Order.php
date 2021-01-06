<?php

declare(strict_types=1);

namespace App\Nova;

use Laravel\Nova\Panel;

use NovaIcon\Icon;
use Timothyasp\Badge\Badge;
use Illuminate\Http\Request;
use App\Nova\Actions\LockOrder;
use App\Nova\Actions\OrderDetails;
use App\Nova\Filters\OrderFilter;
use AwesomeNova\Cards\FilterCard;
use Superlatif\NovaTagInput\Tags;
use Laravel\Nova\Fields\{BelongsTo, ID, KeyValue, Number, Stack, Text};

class Order extends Resource
{
	/**
	 * The model the resource corresponds to.
	 *
	 * @var string
	 */
	public static $model = \App\Models\Order::class;

	/**
	 * The logical group associated with the resource.
	 *
	 * @var string
	 */
	public static $group = null;

	/**
	 * The order in which to show the resource on the sidebar.
	 *
	 * @var int $priority
	 */
	public static $priority = 1;

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
		'purchase',
		'service',
		'summoner',
		'server',
		// TODO: search by transaction id
	];

	/**
	 * The relationship columns that should be searched.
	 *
	 * @var array
	 */
	public static $searchRelations = [
		'booster' => ['first_name', 'last_name', 'username'],
		'client' => ['first_name', 'last_name', 'username', 'email']
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
			Stack::make('Status', [
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
					])->displayUsingLabels()
			])->sortable()->exceptOnForms(),
			ID::make(__('ID'), 'id')->sortable()->hideFromDetail(),
			Text::make(__('Purchase'), fn (): string => $this->purchase . '</br>' . country_flag($this->client->country) . ' ' . $this->service)->asHtml()->onlyOnIndex(),
			// Order details in colored pills
			Tags::make(__("Order details"), fn () => $this->options),
			Text::make(__('Price'), 'price')
				->displayUsing(fn ($price) => '$' . $price)
				->hideFromDetail(),
			Text::make(__('Login name'), 'riot_login')->hideFromIndex(),
			Text::make(__('Login password'), 'riot_password')->hideFromIndex(),
			Text::make(__('Summoner name'), 'summoner')->hideFromIndex(),
			Text::make(__('Server'), 'server')->hideFromIndex(),
			// Current rank?
			ID::make(__('Order ID'), 'id')->sortable()->hideFromIndex(),
			Text::make(__('Item'), 'purchase')->hideFromIndex(),
			Text::make(__('Service'), 'service')->hideFromIndex(),
			// Customer's country => this client country
			Text::make(__('Customer\'s country'), fn () => $this->client->country)->hideFromIndex(),
			BelongsTo::make(__('Booster'), 'booster', User::class),
			BelongsTo::make(__('Client'), 'client', User::class)->hideFromIndex(),
			KeyValue::make(__('Order details'), 'options'),
		];
	}

	/**
	 * The icon of the resource.
	 *
	 * @return string
	 */
	public static function icon()
	{
		// Assuming you have a blade file containing an image
		// in resources/views/vendor/nova/svg/icon-user.blade.php
		return view('nova::svg.icon-order')->render();
	}

	/**
	 * Get the cards available for the request.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function cards(Request $request)
	{
		return [
			new FilterCard(new OrderFilter()),
		];
	}

	/**
	 * Get the filters available for the resource.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return array
	 */
	public function filters(Request $request)
	{
		return [
			new OrderFilter(),
		];
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
