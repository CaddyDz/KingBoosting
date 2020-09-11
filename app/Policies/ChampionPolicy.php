<?php

namespace App\Policies;

use App\Champion;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChampionPolicy
{
   use HandlesAuthorization;

   /**
	* Determine whether the user can view any champions.
	*
	* @param  \App\User  $user
	* @return mixed
	*/
   public function viewAny(User $user)
   {
	  return false;
   }

   /**
	* Determine whether the user can view the champion.
	*
	* @param  \App\User  $user
	* @param  \App\Champion  $champion
	* @return mixed
	*/
   public function view(User $user, Champion $champion)
   {
	  return false;
   }

   /**
	* Determine whether the user can create champions.
	*
	* @param  \App\User  $user
	* @return mixed
	*/
   public function create(User $user)
   {
	  return false;
   }

   /**
	* Determine whether the user can update the champion.
	*
	* @param  \App\User  $user
	* @param  \App\Champion  $champion
	* @return mixed
	*/
   public function update(User $user, Champion $champion)
   {
	  return false;
   }

   /**
	* Determine whether the user can delete the champion.
	*
	* @param  \App\User  $user
	* @param  \App\Champion  $champion
	* @return mixed
	*/
   public function delete(User $user, Champion $champion)
   {
	  return false;
   }

   /**
	* Determine whether the user can restore the champion.
	*
	* @param  \App\User  $user
	* @param  \App\Champion  $champion
	* @return mixed
	*/
   public function restore(User $user, Champion $champion)
   {
	  return false;
   }

   /**
	* Determine whether the user can permanently delete the champion.
	*
	* @param  \App\User  $user
	* @param  \App\Champion  $champion
	* @return mixed
	*/
   public function forceDelete(User $user, Champion $champion)
   {
	  return false;
   }
}
