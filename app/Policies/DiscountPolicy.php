<?php

namespace App\Policies;

use App\Discount;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DiscountPolicy
{
	use HandlesAuthorization;

	/**
	 * Determine whether the user can view any discounts.
	 *
	 * @param  \App\User  $user
	 * @return mixed
	 */
	public function viewAny(User $user)
	{
		return false;
	}

	/**
	 * Determine whether the user can view the discount.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Discount  $discount
	 * @return mixed
	 */
	public function view(User $user, Discount $discount)
	{
		return false;
	}

	/**
	 * Determine whether the user can create discounts.
	 *
	 * @param  \App\User  $user
	 * @return mixed
	 */
	public function create(User $user)
	{
		return false;
	}

	/**
	 * Determine whether the user can update the discount.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Discount  $discount
	 * @return mixed
	 */
	public function update(User $user, Discount $discount)
	{
		return false;
	}

	/**
	 * Determine whether the user can delete the discount.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Discount  $discount
	 * @return mixed
	 */
	public function delete(User $user, Discount $discount)
	{
		return false;
	}

	/**
	 * Determine whether the user can restore the discount.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Discount  $discount
	 * @return mixed
	 */
	public function restore(User $user, Discount $discount)
	{
		return false;
	}

	/**
	 * Determine whether the user can permanently delete the discount.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Discount  $discount
	 * @return mixed
	 */
	public function forceDelete(User $user, Discount $discount)
	{
		return false;
	}
}
