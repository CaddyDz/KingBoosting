<?php

namespace App\Policies;

use App\ServiceTypePivot;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceTypePivotPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any service type pivots.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the service type pivot.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceTypePivot  $serviceTypePivot
     * @return mixed
     */
    public function view(User $user, ServiceTypePivot $serviceTypePivot)
    {
        return false;
    }

    /**
     * Determine whether the user can create service type pivots.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the service type pivot.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceTypePivot  $serviceTypePivot
     * @return mixed
     */
    public function update(User $user, ServiceTypePivot $serviceTypePivot)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the service type pivot.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceTypePivot  $serviceTypePivot
     * @return mixed
     */
    public function delete(User $user, ServiceTypePivot $serviceTypePivot)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the service type pivot.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceTypePivot  $serviceTypePivot
     * @return mixed
     */
    public function restore(User $user, ServiceTypePivot $serviceTypePivot)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the service type pivot.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceTypePivot  $serviceTypePivot
     * @return mixed
     */
    public function forceDelete(User $user, ServiceTypePivot $serviceTypePivot)
    {
        return false;
    }
}
