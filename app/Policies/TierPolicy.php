<?php

namespace App\Policies;

use App\Tier;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TierPolicy
{
	use HandlesAuthorization;

	/**
	 * Determine whether the user can view any tiers.
	 *
	 * @param  \App\User  $user
	 * @return mixed
	 */
	public function viewAny(User $user)
	{
		return false;
	}

	/**
	 * Determine whether the user can view the tier.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Tier  $tier
	 * @return mixed
	 */
	public function view(User $user, Tier $tier)
	{
		return false;
	}

	/**
	 * Determine whether the user can create tiers.
	 *
	 * @param  \App\User  $user
	 * @return mixed
	 */
	public function create(User $user)
	{
		return false;
	}

	/**
	 * Determine whether the user can update the tier.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Tier  $tier
	 * @return mixed
	 */
	public function update(User $user, Tier $tier)
	{
		return false;
	}

	/**
	 * Determine whether the user can delete the tier.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Tier  $tier
	 * @return mixed
	 */
	public function delete(User $user, Tier $tier)
	{
		return false;
	}

	/**
	 * Determine whether the user can restore the tier.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Tier  $tier
	 * @return mixed
	 */
	public function restore(User $user, Tier $tier)
	{
		return false;
	}

	/**
	 * Determine whether the user can permanently delete the tier.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Tier  $tier
	 * @return mixed
	 */
	public function forceDelete(User $user, Tier $tier)
	{
		return false;
	}
}
