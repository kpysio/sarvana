<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FoodItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $foodItems = auth()->user()->foodItems()->latest()->paginate(10);
        return view('food-items.index', compact('foodItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $clone = null;
        if ($request->has('clone_id')) {
            $clone = FoodItem::where('provider_id', auth()->id())->find($request->input('clone_id'));
        }
        return view('food-items.create', compact('clone'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'available_quantity' => 'required|integer|min:1',
            'available_date' => 'required|date|after_or_equal:today',
            'available_time' => 'required',
            'pickup_address' => 'required|string',
            'order_type' => 'required|in:daily,subscription,custom',
            'expiry_date' => 'nullable|date|after_or_equal:today',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['provider_id'] = auth()->id();
        $validated['status'] = 'active';

        // If daily, set expiry_date to available_date
        if ($validated['order_type'] === 'daily') {
            $validated['expiry_date'] = $validated['available_date'];
        }

        // Handle photo uploads
        if ($request->hasFile('photos')) {
            $photos = [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('food-items', 'public');
                $photos[] = $path;
            }
            $validated['photos'] = $photos;
        }

        FoodItem::create($validated);

        return redirect()->route('provider.food-items.index')->with('success', 'Food item created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(FoodItem $foodItem)
    {
        $this->authorize('view', $foodItem);
        $foodItem->load(['provider', 'tags', 'reviews.reviewer']);
        return view('food-items.show', compact('foodItem'));
    }

    /**
     * Display the specified food item for customers/public.
     */
    public function publicShow(FoodItem $foodItem)
    {
        // Only show if active/available
        if ($foodItem->status !== 'active') {
            abort(404);
        }
        $foodItem->load(['provider', 'tags', 'reviews.reviewer']);
        return view('customers.food-items.show', compact('foodItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FoodItem $foodItem)
    {
        $this->authorize('update', $foodItem);
        return view('food-items.edit', compact('foodItem'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FoodItem $foodItem)
    {
        $this->authorize('update', $foodItem);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'available_quantity' => 'required|integer|min:0',
            'available_date' => 'required|date',
            'available_time' => 'required',
            'pickup_address' => 'required|string',
            'status' => 'required|in:active,inactive,sold_out',
            'order_type' => 'required|in:daily,subscription,custom',
            'expiry_date' => 'nullable|date|after_or_equal:today',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // If daily, set expiry_date to available_date
        if ($validated['order_type'] === 'daily') {
            $validated['expiry_date'] = $validated['available_date'];
        }

        // Handle photo uploads
        if ($request->hasFile('photos')) {
            $photos = $foodItem->photos ?? [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('food-items', 'public');
                $photos[] = $path;
            }
            $validated['photos'] = $photos;
        }

        $foodItem->update($validated);

        return redirect()->route('provider.food-items.index')->with('success', 'Food item updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FoodItem $foodItem)
    {
        $this->authorize('delete', $foodItem);

        // Delete associated photos
        if ($foodItem->photos) {
            foreach ($foodItem->photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        $foodItem->delete();

        return redirect()->route('provider.food-items.index')->with('success', 'Food item deleted successfully!');
    }

    public function clone(FoodItem $foodItem)
    {
        $this->authorize('update', $foodItem);
        return redirect()->route('provider.food-items.create', ['clone_id' => $foodItem->id]);
    }

    public function reactivate(FoodItem $foodItem)
    {
        $this->authorize('update', $foodItem);
        $foodItem->status = 'active';
        // Optionally extend expiry to tomorrow if already expired
        if ($foodItem->isExpired()) {
            $foodItem->expiry_date = now()->addDay();
        }
        $foodItem->save();
        return back()->with('success', 'Food item reactivated!');
    }

    public function extendExpiry(Request $request, FoodItem $foodItem)
    {
        $this->authorize('update', $foodItem);
        $request->validate(['days' => 'required|integer|min:1|max:365']);
        $foodItem->expiry_date = $foodItem->expiry_date ? \Carbon\Carbon::parse($foodItem->expiry_date)->addDays($request->days) : now()->addDays($request->days);
        $foodItem->status = 'active';
        $foodItem->save();
        return back()->with('success', 'Expiry extended!');
    }

    public function markSoldOut(FoodItem $foodItem)
    {
        $this->authorize('update', $foodItem);
        $foodItem->status = 'sold_out';
        $foodItem->save();
        return back()->with('success', 'Food item marked as sold out!');
    }
}
