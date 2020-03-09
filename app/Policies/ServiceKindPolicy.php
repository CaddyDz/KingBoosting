<?php

namespace App\Policies;

use App\ServiceKind;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServiceKindPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any service kinds.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the service kind.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceKind  $serviceKind
     * @return mixed
     */
    public function view(User $user, ServiceKind $serviceKind)
    {
        return false;
    }

    /**
     * Determine whether the user can create service kinds.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the service kind.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceKind  $serviceKind
     * @return mixed
     */
    public function update(User $user, ServiceKind $serviceKind)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the service kind.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceKind  $serviceKind
     * @return mixed
     */
    public function delete(User $user, ServiceKind $serviceKind)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the service kind.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceKind  $serviceKind
     * @return mixed
     */
    public function restore(User $user, ServiceKind $serviceKind)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the service kind.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceKind  $serviceKind
     * @return mixed
     */
    public function forceDelete(User $user, ServiceKind $serviceKind)
    {
        return false;
    }
}
