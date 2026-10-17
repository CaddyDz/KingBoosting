<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\Earnings;
use App\Models\{Order, Payout, User};
use Illuminate\Http\{JsonResponse, Request};
use App\Http\Resources\{FineResource, PayoutResource};

class EarningsController extends Controller
{
	/**
	 * The booster's earnings: what's owed and why, and the payouts so far.
	 */
	public function __invoke(Request $request, Earnings $earnings): JsonResponse
	{
		$this->authorize('viewEarnings', Payout::class);

		/** @var User $booster */
		$booster = $request->user();

		return response()->json(['data' => [
			'summary' => $earnings->summary($booster),
			'orders' => $earnings->unpaidOrders($booster)->latest('completed_at')->get()->map(fn (Order $order): array => [
				'id' => $order->id,
				'title' => $order->title,
				'game' => $order->game->label(),
				'completed_at' => $order->completed_at,
				'share_cents' => $order->boosterShareCents(),
			]),
			'fines' => FineResource::collection($booster->fines()->latest()->get()),
			'payouts' => PayoutResource::collection($booster->payouts()->latest('paid_at')->get()),
		]]);
	}
}
