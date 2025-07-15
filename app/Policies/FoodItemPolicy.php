<?php

namespace App\Policies;

use App\Models\User;
use App\Models\FoodItem;

class FoodItemPolicy
{
    public function update(User $user, FoodItem $foodItem)
    {
        return $user->id === $foodItem->provider_id;
    }

    public function delete(User $user, FoodItem $foodItem)
    {
        return $user->id === $foodItem->provider_id;
    }

    public function view(User $user, FoodItem $foodItem)
    {
        return $user->id === $foodItem->provider_id;
    }
} 