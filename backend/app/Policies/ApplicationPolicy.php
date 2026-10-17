<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\{Application, User};

/**
 * Anyone can apply to boost, signed in or not; admins read the applications.
 */
class ApplicationPolicy
{
	public function create(?User $user): bool
	{
		return true;
	}

	public function viewAny(User $user): bool
	{
		return $user->isAdmin();
	}

	public function review(User $user, Application $application): bool
	{
		return $user->isAdmin();
	}
}
