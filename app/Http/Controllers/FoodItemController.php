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
    public function create()
    {
        return view('food-items.create');
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
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['provider_id'] = auth()->id();
        $validated['status'] = 'active';

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

        return redirect()->route('food-items.index')->with('success', 'Food item created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(FoodItem $foodItem)
    {
        // $this->authorize('view', $foodItem);
        return view('food-items.show', compact('foodItem'));
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
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

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

        return redirect()->route('food-items.index')->with('success', 'Food item updated successfully!');
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

        return redirect()->route('food-items.index')->with('success', 'Food item deleted successfully!');
    }
}
