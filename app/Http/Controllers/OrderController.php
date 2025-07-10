<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\FoodItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isProvider()) {
            $orders = $user->providerOrders()->with(['customer', 'foodItem'])->latest()->paginate(10);
        } else {
            $orders = $user->customerOrders()->with(['provider', 'foodItem'])->latest()->paginate(10);
        }
        
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('orders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'food_item_id' => 'required|exists:food_items,id',
            'provider_id' => 'required|exists:users,id',
            'quantity' => 'required|integer|min:1',
            'pickup_time' => 'required|date|after:now',
            'customer_notes' => 'nullable|string',
        ]);

        // Get the food item
        $foodItem = FoodItem::findOrFail($validated['food_item_id']);
        
        // Check if item is available
        if ($foodItem->status !== 'active' || $foodItem->available_quantity < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'This item is not available in the requested quantity.']);
        }

        // Calculate total amount
        $totalAmount = $foodItem->price * $validated['quantity'];

        // Create the order
        $order = Order::create([
            'customer_id' => auth()->id(),
            'provider_id' => $validated['provider_id'],
            'food_item_id' => $validated['food_item_id'],
            'quantity' => $validated['quantity'],
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'pickup_time' => $validated['pickup_time'],
            'customer_notes' => $validated['customer_notes'],
        ]);

        // Update food item quantity
        $foodItem->decrement('available_quantity', $validated['quantity']);

        return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $this->authorize('update', $order);
        return view('orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $order->update($validated);

        return redirect()->route('orders.show', $order)->with('success', 'Order updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);

        // Only allow cancellation if order is pending
        if ($order->status !== 'pending') {
            return back()->withErrors(['status' => 'Only pending orders can be cancelled.']);
        }

        // Update food item quantity back
        $order->foodItem->increment('available_quantity', $order->quantity);

        $order->update(['status' => 'cancelled']);

        return redirect()->route('orders.index')->with('success', 'Order cancelled successfully!');
    }
}
