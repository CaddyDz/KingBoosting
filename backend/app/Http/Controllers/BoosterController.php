<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class BoosterController extends Controller
{
	/**
	 * The boosters a customer can pick at checkout. User::booted() clears the cache when one changes.
	 */
	public function index(): JsonResponse
	{
		$boosters = cache()->remember(User::VISIBLE_BOOSTERS_CACHE, now()->addHour(), fn (): array => User::query()
			->where('role', 'booster')
			->where('visible', true)
			->orderBy('username')
			->get(['id', 'username'])
			->map(fn (User $booster): array => ['id' => $booster->id, 'username' => $booster->username])
			->all());

		return response()->json(['data' => $boosters]);
	}
}
