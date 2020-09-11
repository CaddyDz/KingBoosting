<?php

namespace App\Policies;

use App\User;
use App\AccountSelling;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountSellingPolicy
{
	use HandlesAuthorization;

	/**
	 * Determine whether the user can view any account sellings.
	 *
	 * @param  \App\User  $user
	 * @return mixed
	 */
	public function viewAny(User $user)
	{
		return true;
	}

	/**
	 * Determine whether the user can view the account selling.
	 *
	 * @param  \App\User  $user
	 * @param  \App\AccountSelling  $accountSelling
	 * @return mixed
	 */
	public function view(User $user, AccountSelling $accountSelling)
	{
		return false;
	}

	/**
	 * Determine whether the user can create account sellings.
	 *
	 * @param  \App\User  $user
	 * @return mixed
	 */
	public function create(User $user)
	{
		return false;
	}

	/**
	 * Determine whether the user can update the account selling.
	 *
	 * @param  \App\User  $user
	 * @param  \App\AccountSelling  $accountSelling
	 * @return mixed
	 */
	public function update(User $user, AccountSelling $accountSelling)
	{
		return false;
	}

	/**
	 * Determine whether the user can delete the account selling.
	 *
	 * @param  \App\User  $user
	 * @param  \App\AccountSelling  $accountSelling
	 * @return mixed
	 */
	public function delete(User $user, AccountSelling $accountSelling)
	{
		return false;
	}

	/**
	 * Determine whether the user can restore the account selling.
	 *
	 * @param  \App\User  $user
	 * @param  \App\AccountSelling  $accountSelling
	 * @return mixed
	 */
	public function restore(User $user, AccountSelling $accountSelling)
	{
		return false;
	}

	/**
	 * Determine whether the user can permanently delete the account selling.
	 *
	 * @param  \App\User  $user
	 * @param  \App\AccountSelling  $accountSelling
	 * @return mixed
	 */
	public function forceDelete(User $user, AccountSelling $accountSelling)
	{
		return false;
	}
}
