<?php

namespace App\Policies;

use App\Feature;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FeaturePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any features.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the feature.
     *
     * @param  \App\User  $user
     * @param  \App\Feature  $feature
     * @return mixed
     */
    public function view(User $user, Feature $feature)
    {
        return false;
    }

    /**
     * Determine whether the user can create features.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the feature.
     *
     * @param  \App\User  $user
     * @param  \App\Feature  $feature
     * @return mixed
     */
    public function update(User $user, Feature $feature)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the feature.
     *
     * @param  \App\User  $user
     * @param  \App\Feature  $feature
     * @return mixed
     */
    public function delete(User $user, Feature $feature)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the feature.
     *
     * @param  \App\User  $user
     * @param  \App\Feature  $feature
     * @return mixed
     */
    public function restore(User $user, Feature $feature)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the feature.
     *
     * @param  \App\User  $user
     * @param  \App\Feature  $feature
     * @return mixed
     */
    public function forceDelete(User $user, Feature $feature)
    {
        return false;
    }
}
