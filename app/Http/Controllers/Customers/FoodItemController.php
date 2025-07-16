<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\FoodItem;

class FoodItemController extends Controller
{
    public function show(FoodItem $foodItem)
    {
        if ($foodItem->status !== 'active') {
            abort(404);
        }
        $foodItem->load(['provider', 'tags', 'reviews.reviewer']);
        return view('customers.food-items.show', compact('foodItem'));
    }
} 