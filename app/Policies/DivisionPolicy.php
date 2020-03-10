<?php

namespace App\Policies;

use App\Division;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DivisionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any divisions.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the division.
     *
     * @param  \App\User  $user
     * @param  \App\Division  $division
     * @return mixed
     */
    public function view(User $user, Division $division)
    {
        return false;
    }

    /**
     * Determine whether the user can create divisions.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the division.
     *
     * @param  \App\User  $user
     * @param  \App\Division  $division
     * @return mixed
     */
    public function update(User $user, Division $division)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the division.
     *
     * @param  \App\User  $user
     * @param  \App\Division  $division
     * @return mixed
     */
    public function delete(User $user, Division $division)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the division.
     *
     * @param  \App\User  $user
     * @param  \App\Division  $division
     * @return mixed
     */
    public function restore(User $user, Division $division)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the division.
     *
     * @param  \App\User  $user
     * @param  \App\Division  $division
     * @return mixed
     */
    public function forceDelete(User $user, Division $division)
    {
        return false;
    }
}
