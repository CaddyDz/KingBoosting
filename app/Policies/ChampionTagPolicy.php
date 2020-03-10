<?php

namespace App\Policies;

use App\ChampionTag;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChampionTagPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any champion tags.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the champion tag.
     *
     * @param  \App\User  $user
     * @param  \App\ChampionTag  $championTag
     * @return mixed
     */
    public function view(User $user, ChampionTag $championTag)
    {
        return false;
    }

    /**
     * Determine whether the user can create champion tags.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the champion tag.
     *
     * @param  \App\User  $user
     * @param  \App\ChampionTag  $championTag
     * @return mixed
     */
    public function update(User $user, ChampionTag $championTag)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the champion tag.
     *
     * @param  \App\User  $user
     * @param  \App\ChampionTag  $championTag
     * @return mixed
     */
    public function delete(User $user, ChampionTag $championTag)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the champion tag.
     *
     * @param  \App\User  $user
     * @param  \App\ChampionTag  $championTag
     * @return mixed
     */
    public function restore(User $user, ChampionTag $championTag)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the champion tag.
     *
     * @param  \App\User  $user
     * @param  \App\ChampionTag  $championTag
     * @return mixed
     */
    public function forceDelete(User $user, ChampionTag $championTag)
    {
        return false;
    }
}
