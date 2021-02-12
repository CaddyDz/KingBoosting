<?php

declare(strict_types=1);

namespace App\Nova;

use NovaIcon\Icon;
use NovaButton\Button;
use IDF\HtmlCard\HtmlCard;
use Timothyasp\Badge\Badge;
use Illuminate\Http\Request;
use Sitando\NovaChat\NovaChat;
use AwesomeNova\Cards\FilterCard;
use Superlatif\NovaTagInput\Tags;
use App\Models\Order as ModelsOrder;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Filters\{OrderFilter, OrderServiceFilter};
use Laravel\Nova\Fields\{BelongsTo, ID, KeyValue, Number, Select, Stack, Text};
use App\Nova\Actions\{AssignOrder, EditOrderLoginDetails, MarkOrderAsCompleted, MarkOrderAsPaid, MarkOrderProgress, PauseOrder, ReleaseOrder};

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
	public static $title = 'purchase';

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
	 * Build an "index" query for the given resource.
	 *
	 * @param \Laravel\Nova\Http\Requests\NovaRequest $request
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public static function indexQuery(NovaRequest $request, $query)
	{
		if ($request->user()->hasRole('Member')) {
			return $query->where('client_id', auth()->id());
		} else if ($request->user()->hasRole('Booster')) {
			return $query->where('status', 'pending')
				->orWhere('booster_id', auth()->id());
		}
		return $query;
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
			Stack::make('Status', [
				Icon::make('')
					->icon(
						fn (): string => 'entypo:' . $this->icon
					)->css(fn (): array => [[
						'pending' => 'text-info',
						'progress' => 'text-warning-dark',
						'paused' => 'text-black',
						'suspended' => 'text-danger',
						'completed' => 'text-success',
						'paid' => 'text-success',
					][$this->status ?? 'pending'], 'h-1', 'w-5']),
				Badge::make('Status')
					->options([
						'pending' => __('Awaiting for booster'),
						'progress' => __('In Progress'),
						'paused' => __('Paused'),
						'suspended' => __('Suspended'),
						'completed' => __('Complete'),
						'paid' => __('Paid')
					])
					->colors([
						'pending' => '#64cedb',
						'progress' => '#d68842',
						'paused' => '#000',
						'suspended' => '#ca404d',
						'completed' => '#42d6a9',
						'paid' => '#42d6a9',
					])->displayUsingLabels()
			])->sortable()->exceptOnForms(),
			ID::make(__('ID'), 'id')->sortable()->hideFromDetail(),
			Text::make(__('Purchase'), fn (): string => $this->purchase . '</br>' . country_flag($this->client->country) . ' ' . $this->service)->asHtml()->onlyOnIndex(),
			Select::make(__('Status'), 'status')->options([
				'pending' => __('Awaiting for booster'),
				'progress' => __('In Progress'),
				'paused' => __('Paused'),
				'suspended' => __('Suspended'),
				'completed' => __('Complete'),
				'paid' => __('Paid'),
			])->displayUsingLabels()
				->canSee(fn ($request) => $request->user()->hasRole('Admin'))
				->hideFromIndex(),
			// Order details in colored pills
			Tags::make(__("Order details"), fn () => $this->options),
			Text::make(__('Price'), 'price')
				->displayUsing(fn ($price) => '$' . $price)
				->hideFromDetail()
				->canSee(fn ($request) => $request->user()->hasRole('Admin')),
			Text::make(__('Price'), fn () => $this->price * $this->share / 100)
				->displayUsing(fn ($price) => '$' . $price)
				->hideFromDetail()
				->canSee(fn ($request) => $request->user()->hasRole('Booster')),
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
			BelongsTo::make(__('Client'), 'client', User::class)
				->hideFromIndex()
				->canSee(fn ($request) => !$request->user()->hasRole('Member')),
			NovaChat::make()->order($this->resource)
				->auth_id(auth()->id())
				->canSee(fn (): bool => (bool) $this->booster),
			Button::make(__('Lock'), 'lock-order')
				->reload()
				->style('success')
				->canSee(fn ($request) => $request->user()->hasRole('Booster') && $this->status == 'pending'),
			Number::make(__('Share'), 'share')
				->displayUsing(fn ($share) => '%' . $share)
				->canSee(fn ($request) => $request->user()->hasRole('Admin')),
			KeyValue::make(__('Details'), 'options')->onlyOnForms(),
			Text::make(__('Champion'), 'champion'),
		];
	}

	/**
	 * The icon of the resource.
	 *
	 * @return string
	 */
	public static function icon(): string
	{
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
		// Check if we're on the details view
		// resourceId would only be available then
		// Load the booster card if resource is available
		$cards = [
			new FilterCard(new OrderFilter()),
		];
		if ($request->resourceId) {
			$booster_card = (new HtmlCard())->width('1/2')
				->view('booster', ['booster' => ModelsOrder::find($request->resourceId)->booster])
				->onlyOnDetail()
				->withoutCardStyles();
			array_push($cards, $booster_card);
		}
		return $cards;
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
			new OrderServiceFilter(),
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
		return [
			(new EditOrderLoginDetails)
				->showOnTableRow()
				->canSee(fn ($request) => !$request->user()->hasRole('Booster'))
				->canRun(fn ($request) => !$request->user()->hasRole('Booster')),
			(new PauseOrder)
				->showOnTableRow()
				->canSee(fn ($request) => ($request->user()->is($this->client) || $request->user()->hasRole('Admin')) && $this->status == 'progress'),
			(new MarkOrderAsPaid)
				->showOnTableRow()
				->canRun(fn ($request) => $request->user()->hasRole('Admin'))
				->canSee(fn ($request) => $request->user()->hasRole('Admin')),
			(new MarkOrderAsCompleted)
				->showOnTableRow()
				->canSee(fn ($request) => !$request->user()->hasRole('Member') && $this->status != 'paid')
				->canRun(fn ($request) => !$request->user()->hasRole('Member') && $this->status != 'paid'),
			(new ReleaseOrder)
				->showOnTableRow()
				->canSee(fn ($request) => !$request->user()->hasRole('Member'))
				->canRun(fn ($request) => !$request->user()->hasRole('Member')),
			(new AssignOrder)
				->showOnTableRow()
				->canSee(fn ($request) => $request->user()->hasRole('Admin')),
			(new MarkOrderProgress)
				->showOnTableRow()
				->canSee(fn ($request) => $request->user()->hasRole('Admin') && $this->status == 'completed'),
		];
	}
}
