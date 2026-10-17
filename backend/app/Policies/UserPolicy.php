<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Managing users is admin work. Boosters choose for themselves whether customers see them.
 */
class UserPolicy
{
	public function viewAny(User $user): bool
	{
		return $user->isAdmin();
	}

	/**
	 * Change a user's role. Admins can't change their own, so the last admin can't lock everyone out.
	 */
	public function updateRole(User $user, User $target): Response
	{
		if (! $user->isAdmin()) {
			return Response::deny();
		}

		return $user->is($target) ? Response::deny('You can\'t change your own role.') : Response::allow();
	}

	/**
	 * Show or hide a booster in the booster list and at checkout.
	 */
	public function updateVisibility(User $user, User $target): bool
	{
		return $target->isBooster() && ($user->isAdmin() || $user->is($target));
	}

	/**
	 * Send a user a message from support.
	 */
	public function notify(User $user, User $recipient): bool
	{
		return $user->isAdmin();
	}
}
