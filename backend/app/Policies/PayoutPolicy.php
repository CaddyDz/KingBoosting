<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

/**
 * Money is admin work; boosters see their own earnings.
 */
class PayoutPolicy
{
	public function viewAny(User $user): bool
	{
		return $user->isAdmin();
	}

	public function create(User $user): bool
	{
		return $user->isAdmin();
	}

	/**
	 * The booster's own earnings page.
	 */
	public function viewEarnings(User $user): bool
	{
		return $user->isBooster();
	}
}
