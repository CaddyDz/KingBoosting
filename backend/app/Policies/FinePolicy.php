<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\{Fine, User};
use Illuminate\Auth\Access\Response;

/**
 * Admins issue, change and withdraw fines; boosters see theirs on the earnings page.
 */
class FinePolicy
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
	 * A fine already deducted from a payout can't change.
	 */
	public function update(User $user, Fine $fine): Response
	{
		if (! $user->isAdmin()) {
			return Response::deny();
		}

		return $fine->payout_id === null
			? Response::allow()
			: Response::denyWithStatus(409, 'This fine was already deducted from a payout.');
	}

	public function delete(User $user, Fine $fine): Response
	{
		return $this->update($user, $fine);
	}
}
