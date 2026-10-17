<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Support\Earnings;
use App\Models\{Payout, User};
use App\Exceptions\OrderConflict;
use App\Http\Controllers\Controller;
use App\Http\Resources\PayoutResource;
use App\Actions\Payouts\PayOutBooster;
use Illuminate\Validation\Rule;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PayoutController extends Controller
{
	/**
	 * Payout history, newest first.
	 */
	public function index(): AnonymousResourceCollection
	{
		$this->authorize('viewAny', Payout::class);

		return PayoutResource::collection(Payout::query()->with('booster')->latest('paid_at')->latest('id')->paginate(20));
	}

	/**
	 * Every booster with completed orders waiting for a payout, and what they're owed.
	 */
	public function owed(Earnings $earnings): JsonResponse
	{
		$this->authorize('viewAny', Payout::class);

		$boosters = User::query()
			->where('role', 'booster')
			->whereHas('jobs', fn ($query) => $query->where('status', OrderStatus::Completed)->whereNull('payout_id'))
			->orderBy('username')
			->get();

		return response()->json(['data' => $boosters->map(fn (User $booster): array => [
			'booster' => ['id' => $booster->id, 'username' => $booster->username],
			...$earnings->summary($booster),
		])]);
	}

	/**
	 * Pay several boosters at once. Boosters with nothing payable are reported back, not paid.
	 */
	public function store(Request $request, PayOutBooster $payOut): JsonResponse
	{
		$this->authorize('create', Payout::class);
		$validated = $request->validate([
			'booster_ids' => ['required', 'array', 'min:1'],
			'booster_ids.*' => ['integer', 'distinct', Rule::exists('users', 'id')->where('role', 'booster')],
		]);

		/** @var User $admin */
		$admin = $request->user();
		$paid = [];
		$skipped = [];
		foreach (User::query()->whereKey($validated['booster_ids'])->get() as $booster) {
			try {
				$paid[] = $payOut->handle($admin, $booster)->load('booster');
			} catch (OrderConflict $conflict) {
				$skipped[] = ['booster_id' => $booster->id, 'reason' => $conflict->getMessage()];
			}
		}

		return response()->json([
			'data' => PayoutResource::collection($paid),
			'skipped' => $skipped,
		], $paid === [] ? 409 : 201);
	}
}
