<?php

namespace App\Policies;

use App\OrderDetails;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderDetailsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any order details.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the order details.
     *
     * @param  \App\User  $user
     * @param  \App\OrderDetails  $orderDetails
     * @return mixed
     */
    public function view(User $user, OrderDetails $orderDetails)
    {
        return false;
    }

    /**
     * Determine whether the user can create order details.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the order details.
     *
     * @param  \App\User  $user
     * @param  \App\OrderDetails  $orderDetails
     * @return mixed
     */
    public function update(User $user, OrderDetails $orderDetails)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the order details.
     *
     * @param  \App\User  $user
     * @param  \App\OrderDetails  $orderDetails
     * @return mixed
     */
    public function delete(User $user, OrderDetails $orderDetails)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the order details.
     *
     * @param  \App\User  $user
     * @param  \App\OrderDetails  $orderDetails
     * @return mixed
     */
    public function restore(User $user, OrderDetails $orderDetails)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the order details.
     *
     * @param  \App\User  $user
     * @param  \App\OrderDetails  $orderDetails
     * @return mixed
     */
    public function forceDelete(User $user, OrderDetails $orderDetails)
    {
        return false;
    }
}
