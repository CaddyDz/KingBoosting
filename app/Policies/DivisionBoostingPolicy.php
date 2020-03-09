<?php

namespace App\Policies;

use App\DivisionBoosting;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DivisionBoostingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any division boostings.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the division boosting.
     *
     * @param  \App\User  $user
     * @param  \App\DivisionBoosting  $divisionBoosting
     * @return mixed
     */
    public function view(User $user, DivisionBoosting $divisionBoosting)
    {
        return false;
    }

    /**
     * Determine whether the user can create division boostings.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the division boosting.
     *
     * @param  \App\User  $user
     * @param  \App\DivisionBoosting  $divisionBoosting
     * @return mixed
     */
    public function update(User $user, DivisionBoosting $divisionBoosting)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the division boosting.
     *
     * @param  \App\User  $user
     * @param  \App\DivisionBoosting  $divisionBoosting
     * @return mixed
     */
    public function delete(User $user, DivisionBoosting $divisionBoosting)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the division boosting.
     *
     * @param  \App\User  $user
     * @param  \App\DivisionBoosting  $divisionBoosting
     * @return mixed
     */
    public function restore(User $user, DivisionBoosting $divisionBoosting)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the division boosting.
     *
     * @param  \App\User  $user
     * @param  \App\DivisionBoosting  $divisionBoosting
     * @return mixed
     */
    public function forceDelete(User $user, DivisionBoosting $divisionBoosting)
    {
        return false;
    }
}
