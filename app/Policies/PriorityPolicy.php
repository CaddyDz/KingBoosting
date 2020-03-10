<?php

namespace App\Policies;

use App\Priority;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PriorityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any priorities.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the priority.
     *
     * @param  \App\User  $user
     * @param  \App\Priority  $priority
     * @return mixed
     */
    public function view(User $user, Priority $priority)
    {
        return false;
    }

    /**
     * Determine whether the user can create priorities.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the priority.
     *
     * @param  \App\User  $user
     * @param  \App\Priority  $priority
     * @return mixed
     */
    public function update(User $user, Priority $priority)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the priority.
     *
     * @param  \App\User  $user
     * @param  \App\Priority  $priority
     * @return mixed
     */
    public function delete(User $user, Priority $priority)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the priority.
     *
     * @param  \App\User  $user
     * @param  \App\Priority  $priority
     * @return mixed
     */
    public function restore(User $user, Priority $priority)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the priority.
     *
     * @param  \App\User  $user
     * @param  \App\Priority  $priority
     * @return mixed
     */
    public function forceDelete(User $user, Priority $priority)
    {
        return false;
    }
}
