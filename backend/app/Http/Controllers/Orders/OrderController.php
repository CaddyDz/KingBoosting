<?php

declare(strict_types=1);

namespace App\Http\Controllers\Orders;

use App\Models\{Order, User};
use Illuminate\Http\JsonResponse;
use App\Actions\Orders\PlaceOrder;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use Illuminate\Database\Eloquent\Builder;
use App\Payments\{OfflinePaymentGateway, PaymentGateway};
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Requests\Orders\{ListOrdersRequest, StoreOrderRequest};

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
			->orderBy('created_at', $direction = $request->validated('sort') === 'oldest' ? 'asc' : 'desc')
			->orderBy('id', $direction)
			->with(['customer', 'booster'])
			->paginate($request->integer('per_page', 15))
			->withQueryString();

		return OrderResource::collection($orders);
	}

	/**
	 * Checkout: the order is priced on the server and returned with the client secret the Payment Element confirms.
	 */
	public function store(StoreOrderRequest $request, PlaceOrder $placeOrder): JsonResponse
	{
		/** @var User $customer */
		$customer = $request->user();
		[$order, $intent] = $placeOrder->handle($customer, $request->validated(), $request->quoteInput());

		return (new OrderResource($order->load(['customer', 'booster'])))
			->withDetails()
			->additional(['client_secret' => $intent->clientSecret])
			->response()
			->setStatusCode(201);
	}

	public function show(Order $order): OrderResource
	{
		$this->authorize('view', $order);

		return (new OrderResource($order->load(['customer', 'booster'])))->withDetails();
	}

	/**
	 * The order's Stripe client secret, so checkout can take (or retry) the payment for an unpaid order.
	 */
	public function payment(Order $order, PaymentGateway $payments): JsonResponse
	{
		$this->authorize('pay', $order);

		return response()->json(['data' => [
			'client_secret' => $payments->clientSecret($order),
			'amount_cents' => $order->price_cents,
			// Paid by simulation on a local stack without Stripe keys
			'offline' => OfflinePaymentGateway::handles($order),
		]]);
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
