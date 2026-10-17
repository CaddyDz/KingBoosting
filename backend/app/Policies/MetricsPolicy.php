<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

/**
 * The business numbers on the admin dashboard.
 */
class MetricsPolicy
{
	public function view(User $user): bool
	{
		return $user->isAdmin();
	}
}
