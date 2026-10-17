<?php

declare(strict_types=1);

namespace App\Http\Controllers\Orders;

use App\Models\{Order, User};
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\Orders\ListOrdersRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
	/**
	 * The dashboard's orders table: staff see every order, boosters the orders they hold,
	 * customers their own. Boosters find pending orders on the jobs board instead.
	 */
	public function index(ListOrdersRequest $request): AnonymousResourceCollection
	{
		/** @var User $user */
		$user = $request->user();

		$orders = Order::query()
			->visibleTo($user)
			->when($user->isBooster(), fn (Builder $query) => $query->where('booster_id', $user->id))
			->when($request->validated('status'), fn (Builder $query, array $statuses) => $query->whereIn('status', $statuses))
			->when($request->validated('game'), fn (Builder $query, string $game) => $query->where('game', $game))
			->when($request->validated('service'), fn (Builder $query, string $service) => $query->where('service', $service))
			->when($request->validated('search'), fn (Builder $query, string $search) => $this->search($query, $search, $user))
			->orderBy('id', $request->validated('sort') === 'oldest' ? 'asc' : 'desc')
			->with(['customer', 'booster'])
			->paginate($request->integer('per_page', 15))
			->withQueryString();

		return OrderResource::collection($orders);
	}

	public function show(Order $order): OrderResource
	{
		$this->authorize('view', $order);

		return (new OrderResource($order->load(['customer', 'booster'])))->withDetails();
	}

	/**
	 * Match the order number, the purchase or the in-game name; staff can also search by customer or booster.
	 *
	 * @param  Builder<Order>  $query
	 */
	private function search(Builder $query, string $search, User $user): void
	{
		$like = '%'.str_replace(['%', '_'], ['\%', '\_'], $search).'%';

		$query->where(function (Builder $query) use ($search, $like, $user): void {
			$query->where('title', 'like', $like)->orWhere('account_name', 'like', $like);

			if (ctype_digit(ltrim($search, '#'))) {
				$query->orWhere('id', (int) ltrim($search, '#'));
			}

			if ($user->isStaff()) {
				$query->orWhereHas('customer', fn (Builder $query) => $query->where('username', 'like', $like)->orWhere('email', 'like', $like))
					->orWhereHas('booster', fn (Builder $query) => $query->where('username', 'like', $like));
			}
		});
	}
}
