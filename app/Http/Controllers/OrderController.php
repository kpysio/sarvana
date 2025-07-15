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
            'customer_notes' => 'nullable|string',
        ]);

        $foodItem = FoodItem::findOrFail($validated['food_item_id']);
        if ($foodItem->status !== 'active' || $foodItem->available_quantity < $validated['quantity']) {
            $msg = ['quantity' => 'This item is not available in the requested quantity.'];
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $msg['quantity']], 422);
            }
            return back()->withErrors($msg);
        }
        $totalAmount = $foodItem->price * $validated['quantity'];

        $order = Order::create([
            'customer_id' => auth()->id(),
            'provider_id' => $validated['provider_id'],
            'food_item_id' => $validated['food_item_id'],
            'quantity' => $validated['quantity'],
            'total_amount' => $totalAmount,
            'status' => Order::STATUS_PENDING,
            'customer_notes' => $validated['customer_notes'] ?? null,
        ]);
        $foodItem->decrement('available_quantity', $validated['quantity']);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'order_id' => $order->id]);
        }
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

    // Provider actions for status transitions
    public function accept(Order $order)
    {
        $this->authorize('update', $order);
        if ($order->isPending()) {
            $order->update(['status' => Order::STATUS_ACCEPTED]);
        }
        return back();
    }
    public function reject(Order $order)
    {
        $this->authorize('update', $order);
        if ($order->isPending()) {
            $order->update(['status' => Order::STATUS_REJECTED]);
            $order->foodItem->increment('available_quantity', $order->quantity);
        }
        return back();
    }
    public function preparing(Order $order)
    {
        $this->authorize('update', $order);
        if ($order->status === Order::STATUS_ACCEPTED) {
            $order->update(['status' => Order::STATUS_PREPARING]);
        }
        return back();
    }
    public function ready(Request $request, Order $order)
    {
        $this->authorize('update', $order);
        $request->validate(['proof_photo' => 'required|image|max:4096']);
        if ($order->status === Order::STATUS_PREPARING) {
            $path = $request->file('proof_photo')->store('order_proofs', 'public');
            $order->update(['status' => Order::STATUS_READY, 'proof_photo' => $path]);
        }
        return back();
    }
    public function collected(Order $order)
    {
        $this->authorize('update', $order);
        if ($order->status === Order::STATUS_READY) {
            $order->update(['status' => Order::STATUS_COLLECTED]);
        }
        return back();
    }
    public function completed(Order $order)
    {
        $this->authorize('update', $order);
        if ($order->status === Order::STATUS_COLLECTED) {
            $order->update(['status' => Order::STATUS_COMPLETED]);
        }
        return back();
    }
    public function cancel(Order $order)
    {
        $this->authorize('delete', $order);
        if ($order->isPending()) {
            $order->foodItem->increment('available_quantity', $order->quantity);
            $order->update(['status' => Order::STATUS_CANCELLED]);
        }
        return back();
    }
}
