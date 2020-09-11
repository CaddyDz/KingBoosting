<?php

namespace App\Policies;

use App\ChampionRole;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChampionRolePolicy
{
	use HandlesAuthorization;

	/**
	 * Determine whether the user can view any champion roles.
	 *
	 * @param  \App\User  $user
	 * @return mixed
	 */
	public function viewAny(User $user)
	{
		return false;
	}

	/**
	 * Determine whether the user can view the champion role.
	 *
	 * @param  \App\User  $user
	 * @param  \App\ChampionRole  $championRole
	 * @return mixed
	 */
	public function view(User $user, ChampionRole $championRole)
	{
		return false;
	}

	/**
	 * Determine whether the user can create champion roles.
	 *
	 * @param  \App\User  $user
	 * @return mixed
	 */
	public function create(User $user)
	{
		return false;
	}

	/**
	 * Determine whether the user can update the champion role.
	 *
	 * @param  \App\User  $user
	 * @param  \App\ChampionRole  $championRole
	 * @return mixed
	 */
	public function update(User $user, ChampionRole $championRole)
	{
		return false;
	}

	/**
	 *
	 * Determine whether the user can delete the champion role.
	 *
	 * @param  \App\User  $user
	 * @param  \App\ChampionRole  $championRole
	 * @return mixed
	 */
	public function delete(User $user, ChampionRole $championRole)
	{
		return false;
	}

	/**
	 * Determine whether the user can restore the champion role.
	 *
	 * @param  \App\User  $user
	 * @param  \App\ChampionRole  $championRole
	 * @return mixed
	 */
	public function restore(User $user, ChampionRole $championRole)
	{
		return false;
	}

	/**
	 * Determine whether the user can permanently delete the champion role.
	 *
	 * @param  \App\User  $user
	 * @param  \App\ChampionRole  $championRole
	 * @return mixed
	 */
	public function forceDelete(User $user, ChampionRole $championRole)
	{
		return false;
	}
}
