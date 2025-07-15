<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FoodItem;
use Illuminate\Support\Facades\Auth;

class FoodItemController extends Controller
{
    // Display a listing of the provider's food items
    public function index()
    {
        $foodItems = FoodItem::where('provider_id', Auth::id())->get();
        return view('food-items.index', compact('foodItems'));
    }

    // Show the form for creating a new food item
    public function create()
    {
        return view('food-items.create');
    }

    // Store a newly created food item
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'available_quantity' => 'required|integer|min:0',
            'expiry_date' => 'nullable|date',
        ]);
        $validated['provider_id'] = Auth::id();
        FoodItem::create($validated);
        return redirect()->route('provider.food-items.index')->with('success', 'Food item created successfully!');
    }

    // Show the form for editing the specified food item
    public function edit(FoodItem $foodItem)
    {
        $this->authorize('update', $foodItem);
        return view('food-items.edit', compact('foodItem'));
    }

    // Update the specified food item
    public function update(Request $request, FoodItem $foodItem)
    {
        $this->authorize('update', $foodItem);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'available_quantity' => 'required|integer|min:0',
            'expiry_date' => 'nullable|date',
        ]);
        $foodItem->update($validated);
        return redirect()->route('provider.food-items.index')->with('success', 'Food item updated successfully!');
    }

    // Show a single food item
    public function show(FoodItem $foodItem)
    {
        $this->authorize('view', $foodItem);
        return view('food-items.show', compact('foodItem'));
    }

    // Remove the specified food item
    public function destroy(FoodItem $foodItem)
    {
        $this->authorize('delete', $foodItem);
        $foodItem->delete();
        return redirect()->route('provider.food-items.index')->with('success', 'Food item deleted successfully!');
    }

    public function extendExpiry(Request $request, FoodItem $foodItem)
    {
        $this->authorize('update', $foodItem);
        $days = (int) $request->input('days', 7);
        if ($foodItem->expiry_date) {
            $foodItem->expiry_date = \Carbon\Carbon::parse($foodItem->expiry_date)->addDays($days);
            $foodItem->save();
        }
        return redirect()->route('provider.food-items.index')->with('success', 'Expiry extended!');
    }

    public function reactivate(FoodItem $foodItem)
    {
        $this->authorize('update', $foodItem);
        $foodItem->status = 'active';
        $foodItem->save();
        return redirect()->route('provider.food-items.index')->with('success', 'Food item reactivated!');
    }

    public function markSoldOut(FoodItem $foodItem)
    {
        $this->authorize('update', $foodItem);
        $foodItem->status = 'inactive';
        $foodItem->save();
        return redirect()->route('provider.food-items.index')->with('success', 'Food item marked as sold out!');
    }
} 