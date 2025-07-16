<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FoodItem;
use Illuminate\Support\Facades\Auth;
use App\Models\Tag;

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
        $tags = Tag::all();
        return view('food-items.create', compact('tags'));
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
            'available_date' => 'required|date|after_or_equal:today',
            'available_time' => 'required',
            'pickup_address' => 'required|string',
            'expiry_date' => 'nullable|date|after_or_equal:today',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        // Fallback: if available_date is missing, set to today
        if (empty($validated['available_date'])) {
            $validated['available_date'] = date('Y-m-d');
        }
        // Fallback: if available_time is missing, set to now
        if (empty($validated['available_time'])) {
            $validated['available_time'] = date('H:i');
        }
        $validated['provider_id'] = Auth::id();
        FoodItem::create($validated);
        return redirect()->route('provider.food-items.index')->with('success', 'Food item created successfully!');
    }

    // Show the form for editing the specified food item
    public function edit(FoodItem $foodItem)
    {
        $this->authorize('update', $foodItem);
        $tags = Tag::all();
        return view('food-items.edit', compact('foodItem', 'tags'));
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
        // Sync tags if present in the request
        if ($request->has('tags')) {
            $foodItem->tags()->sync($request->input('tags'));
        }
        return redirect()->route('provider.food-items.index')->with('success', 'Food item updated successfully!');
    }

    // Show a single food item
    public function show(FoodItem $foodItem)
    {
        $this->authorize('view', $foodItem);
        $orders = $foodItem->orders()->with('customer')->get();
        $orderStats = [
            'total_orders' => $orders->count(),
            'completed_orders' => $orders->where('status', 'completed')->count(),
            'pending_orders' => $orders->where('status', 'pending')->count(),
            'cancelled_orders' => $orders->where('status', 'cancelled')->count(),
            'completion_rate' => $orders->count() ? ($orders->where('status', 'completed')->count() / $orders->count()) * 100 : 0,
        ];
        $orderStatusBreakdown = $orders->groupBy('status')->map->count();
        // Inventory breakdown
        $totalQuantity = $foodItem->available_quantity + $orders->sum('quantity');
        $orderedQuantity = $orders->sum('quantity');
        $remainingQuantity = $foodItem->available_quantity;
        $orderRate = $totalQuantity > 0 ? ($orderedQuantity / $totalQuantity) * 100 : 0;
        $inventoryBreakdown = [
            'total' => $totalQuantity,
            'ordered' => $orderedQuantity,
            'remaining' => $remainingQuantity,
            'order_rate' => $orderRate,
        ];
        return view('food-items.show', compact('foodItem', 'orders', 'orderStats', 'orderStatusBreakdown', 'inventoryBreakdown'));
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