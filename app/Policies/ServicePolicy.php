<?php

namespace App\Policies;

use App\Service;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePolicy
{
	use HandlesAuthorization;

	/**
	 * Determine whether the user can view any services.
	 *
	 * @param  \App\User  $user
	 * @return mixed
	 */
	public function viewAny(User $user)
	{
		return false;
	}

	/**
	 * Determine whether the user can view the service.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Service  $service
	 * @return mixed
	 */
	public function view(User $user, Service $service)
	{
		return false;
	}

	/**
	 * Determine whether the user can create services.
	 *
	 * @param  \App\User  $user
	 * @return mixed
	 */
	public function create(User $user)
	{
		return false;
	}

	/**
	 * Determine whether the user can update the service.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Service  $service
	 * @return mixed
	 */
	public function update(User $user, Service $service)
	{
		return false;
	}

	/**
	 * Determine whether the user can delete the service.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Service  $service
	 * @return mixed
	 */
	public function delete(User $user, Service $service)
	{
		return false;
	}

	/**
	 * Determine whether the user can restore the service.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Service  $service
	 * @return mixed
	 */
	public function restore(User $user, Service $service)
	{
		return false;
	}

	/**
	 * Determine whether the user can permanently delete the service.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Service  $service
	 * @return mixed
	 */
	public function forceDelete(User $user, Service $service)
	{
		return false;
	}
}
