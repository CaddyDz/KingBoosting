<?php

namespace App\Policies;

use App\User;
use App\WinBoosting;
use Illuminate\Auth\Access\HandlesAuthorization;

class WinBoostingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any win boostings.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the win boosting.
     *
     * @param  \App\User  $user
     * @param  \App\WinBoosting  $winBoosting
     * @return mixed
     */
    public function view(User $user, WinBoosting $winBoosting)
    {
        return false;
    }

    /**
     * Determine whether the user can create win boostings.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the win boosting.
     *
     * @param  \App\User  $user
     * @param  \App\WinBoosting  $winBoosting
     * @return mixed
     */
    public function update(User $user, WinBoosting $winBoosting)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the win boosting.
     *
     * @param  \App\User  $user
     * @param  \App\WinBoosting  $winBoosting
     * @return mixed
     */
    public function delete(User $user, WinBoosting $winBoosting)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the win boosting.
     *
     * @param  \App\User  $user
     * @param  \App\WinBoosting  $winBoosting
     * @return mixed
     */
    public function restore(User $user, WinBoosting $winBoosting)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the win boosting.
     *
     * @param  \App\User  $user
     * @param  \App\WinBoosting  $winBoosting
     * @return mixed
     */
    public function forceDelete(User $user, WinBoosting $winBoosting)
    {
        return false;
    }
}
