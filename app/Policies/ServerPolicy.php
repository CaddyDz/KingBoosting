<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\{Server, User};
use Illuminate\Auth\Access\HandlesAuthorization;

class ServerPolicy
{
	use HandlesAuthorization;

	/**
	 * Determine whether the user can view any models.
	 *
	 * @param  \App\Models\User  $user
	 * @return mixed
	 */
	public function viewAny(User $user): bool
	{
		return true;
	}

	/**
	 * Determine whether the user can view the model.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\Server  $server
	 * @return mixed
	 */
	public function view(User $user, Server $server): bool
	{
		return true;
	}

	/**
	 * Determine whether the user can create models.
	 *
	 * @param  \App\Models\User  $user
	 * @return mixed
	 */
	public function create(User $user): bool
	{
		return true;
	}

	/**
	 * Determine whether the user can update the model.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\Server  $server
	 * @return mixed
	 */
	public function update(User $user, Server $server): bool
	{
		return true;
	}

	/**
	 * Determine whether the user can delete the model.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\Server  $server
	 * @return mixed
	 */
	public function delete(User $user, Server $server): bool
	{
		return true;
	}

	/**
	 * Determine whether the user can restore the model.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\Server  $server
	 * @return mixed
	 */
	public function restore(User $user, Server $server): bool
	{
		return true;
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\Server  $server
	 * @return mixed
	 */
	public function forceDelete(User $user, Server $server): bool
	{
		return true;
	}
}
