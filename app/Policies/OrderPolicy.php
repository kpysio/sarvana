<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view orders
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): bool
    {
        // Users can view orders they are involved in (as customer or provider)
        return $user->id === $order->customer_id || $user->id === $order->provider_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isCustomer(); // Only customers can create orders
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): bool
    {
        // Providers can update order status, customers can only update pending orders
        if ($user->isProvider()) {
            return $user->id === $order->provider_id;
        }
        
        if ($user->isCustomer()) {
            return $user->id === $order->customer_id && $order->status === 'pending';
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Order $order): bool
    {
        // Only customers can cancel their own pending orders
        return $user->isCustomer() && 
               $user->id === $order->customer_id && 
               $order->status === 'pending';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Order $order): bool
    {
        return false; // No restore functionality for orders
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Order $order): bool
    {
        return false; // No permanent delete functionality for orders
    }
}
