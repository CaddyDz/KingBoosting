<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\{Fine, User};
use Illuminate\Auth\Access\HandlesAuthorization;

class FinePolicy
{
	use HandlesAuthorization;

	/**
	 * Determine whether the user can view any models.
	 *
	 * @param \App\Models\User $user
	 * @return mixed
	 */
	public function viewAny(User $user): bool
	{
		return !$user->hasRole('Member');
	}

	/**
	 * Determine whether the user can view the model.
	 *
	 * @param \App\Models\User $user
	 * @param \App\Models\Fine $fine
	 * @return mixed
	 */
	public function view(User $user, Fine $fine): bool
	{
		return $user->hasRole('Admin');
	}

	/**
	 * Determine whether the user can create models.
	 *
	 * @param \App\Models\User $user
	 * @return mixed
	 */
	public function create(User $user): bool
	{
		return $user->hasRole('Admin');
	}

	/**
	 * Determine whether the user can update the model.
	 *
	 * @param \App\Models\User $user
	 * @param \App\Models\Fine $fine
	 * @return mixed
	 */
	public function update(User $user, Fine $fine): bool
	{
		return $user->hasRole('Admin');
	}

	/**
	 * Determine whether the user can delete the model.
	 *
	 * @param \App\Models\User $user
	 * @param \App\Models\Fine $fine
	 * @return mixed
	 */
	public function delete(User $user, Fine $fine): bool
	{
		return $user->hasRole('Admin');
	}

	/**
	 * Determine whether the user can restore the model.
	 *
	 * @param \App\Models\User $user
	 * @param \App\Models\Fine $fine
	 * @return mixed
	 */
	public function restore(User $user, Fine $fine): bool
	{
		return false;
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 *
	 * @param \App\Models\User $user
	 * @param \App\Models\Fine $fine
	 * @return mixed
	 */
	public function forceDelete(User $user, Fine $fine): bool
	{
		return false;
	}
}
