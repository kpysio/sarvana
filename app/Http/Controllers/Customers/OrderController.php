<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->customerOrders()->with(['foodItem.provider'])->latest()->get();
        return view('customers.orders', compact('orders'));
    }

    public function show(\App\Models\Order $order)
    {
        $this->authorize('view', $order);
        $order->load(['foodItem.tags', 'foodItem.provider', 'customer']);
        $orderJson = json_encode([
            'id' => $order->id,
            'status' => $order->status,
            'created_at' => $order->created_at->toDateTimeString(),
            'customer' => $order->customer ? [
                'id' => $order->customer->id,
                'name' => $order->customer->name,
            ] : null,
            'food_item' => $order->foodItem ? [
                'id' => $order->foodItem->id,
                'title' => $order->foodItem->title,
            ] : null,
            'quantity' => $order->quantity,
            'pickup_time' => $order->pickup_time ? $order->pickup_time->toDateTimeString() : null,
            'customer_notes' => $order->customer_notes,
        ]);
        return view('customers.orders.show', [
            'order' => $order,
            'orderJson' => $orderJson,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'food_item_id' => 'required|exists:food_items,id',
            'provider_id' => 'nullable|exists:users,id',
            'quantity' => 'required|integer|min:1',
            'customer_notes' => 'nullable|string',
        ]);
        $foodItem = \App\Models\FoodItem::findOrFail($validated['food_item_id']);
        if ($foodItem->status !== 'active' || $foodItem->available_quantity < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'This item is not available in the requested quantity.']);
        }
        $totalAmount = $foodItem->price * $validated['quantity'];
        $order = \App\Models\Order::create([
            'customer_id' => auth()->id(),
            'provider_id' => $validated['provider_id'] ?? $foodItem->provider_id,
            'food_item_id' => $validated['food_item_id'],
            'quantity' => $validated['quantity'],
            'total_amount' => $totalAmount,
            'status' => \App\Models\Order::STATUS_PENDING,
            'customer_notes' => $validated['customer_notes'] ?? null,
        ]);
        $foodItem->decrement('available_quantity', $validated['quantity']);
        return redirect()->route('customers.orders.show', $order->id)->with('success', 'Order placed successfully!');
    }
} 