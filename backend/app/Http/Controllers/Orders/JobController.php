<?php

declare(strict_types=1);

namespace App\Http\Controllers\Orders;

use App\Models\Order;
use App\Enums\{Game, OrderStatus};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class JobController extends Controller
{
	/**
	 * The jobs board: paid orders waiting for a booster, oldest first. Never includes credentials.
	 */
	public function index(Request $request): AnonymousResourceCollection
	{
		$this->authorize('viewJobs', Order::class);

		$request->validate([
			'game' => ['sometimes', Rule::enum(Game::class)],
			'service' => ['sometimes', 'string', 'max:64'],
		]);

		$jobs = Order::query()
			->where('status', OrderStatus::Pending)
			->whereNull('booster_id')
			->when($request->input('game'), fn (Builder $query, string $game) => $query->where('game', $game))
			->when($request->input('service'), fn (Builder $query, string $service) => $query->where('service', $service))
			->oldest('paid_at')
			->oldest('id')
			->with('customer')
			->paginate(20);

		return OrderResource::collection($jobs);
	}
}
