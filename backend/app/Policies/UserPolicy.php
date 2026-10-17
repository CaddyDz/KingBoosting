<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

/**
 * Managing users is admin work (docs/order-rules.md).
 */
class UserPolicy
{
	/**
	 * Send a user a message from support.
	 */
	public function notify(User $user, User $recipient): bool
	{
		return $user->isAdmin();
	}
}
