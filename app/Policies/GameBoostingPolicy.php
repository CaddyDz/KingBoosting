<?php

namespace App\Policies;

use App\GameBoosting;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GameBoostingPolicy
{
	use HandlesAuthorization;

	/**
	 * Determine whether the user can view any game boostings.
	 *
	 * @param  \App\User  $user
	 * @return mixed
	 */
	public function viewAny(User $user)
	{
		return false;
	}

	/**
	 * Determine whether the user can view the game boosting.
	 *
	 * @param  \App\User  $user
	 * @param  \App\GameBoosting  $gameBoosting
	 * @return mixed
	 */
	public function view(User $user, GameBoosting $gameBoosting)
	{
		return false;
	}

	/**
	 * Determine whether the user can create game boostings.
	 *
	 * @param  \App\User  $user
	 * @return mixed
	 */
	public function create(User $user)
	{
		return false;
	}

	/**
	 * Determine whether the user can update the game boosting.
	 *
	 * @param  \App\User  $user
	 * @param  \App\GameBoosting  $gameBoosting
	 * @return mixed
	 */
	public function update(User $user, GameBoosting $gameBoosting)
	{
		return false;
	}

	/**
	 * Determine whether the user can delete the game boosting.
	 *
	 * @param  \App\User  $user
	 * @param  \App\GameBoosting  $gameBoosting
	 * @return mixed
	 */
	public function delete(User $user, GameBoosting $gameBoosting)
	{
		return false;
	}

	/**
	 * Determine whether the user can restore the game boosting.
	 *
	 * @param  \App\User  $user
	 * @param  \App\GameBoosting  $gameBoosting
	 * @return mixed
	 */
	public function restore(User $user, GameBoosting $gameBoosting)
	{
		return false;
	}

	/**
	 * Determine whether the user can permanently delete the game boosting.
	 *
	 * @param  \App\User  $user
	 * @param  \App\GameBoosting  $gameBoosting
	 * @return mixed
	 */
	public function forceDelete(User $user, GameBoosting $gameBoosting)
	{
		return false;
	}
}
