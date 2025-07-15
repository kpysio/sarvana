<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\FoodItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $providerId = Auth::id();
        $orders = Order::with(['foodItem', 'customer'])
            ->whereHas('foodItem', function($q) use ($providerId) {
                $q->where('provider_id', $providerId);
            })
            ->get()
            ->groupBy('status');
        return view('provider.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load(['foodItem', 'customer']);
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
        if (request()->expectsJson() || request()->ajax()) {
            // Return a minimal HTML with the order-json script tag for modal
            return response('<script type="application/json" id="order-json">' . $orderJson . '</script>');
        }
        return view('provider.orders.show', [
            'order' => $order,
            'orderJson' => $orderJson,
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('update', $order);
        $request->validate(['status' => 'required|string']);
        $order->status = $request->status;
        $order->history = collect($order->history ?? [])->push([
            'actor' => 'provider',
            'action' => 'status_update',
            'status' => $request->status,
            'timestamp' => now(),
        ]);
        $order->save();
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'status' => $order->status]);
        }
        return back()->with('success', 'Order status updated!');
    }

    public function setPickupTime(Request $request, Order $order)
    {
        $this->authorize('update', $order);
        $request->validate(['pickup_time' => 'required|date|after:now']);
        $order->pickup_time = $request->pickup_time;
        $order->history = collect($order->history ?? [])->push([
            'actor' => 'provider',
            'action' => 'set_pickup_time',
            'pickup_time' => $request->pickup_time,
            'timestamp' => now(),
        ]);
        $order->save();
        return back()->with('success', 'Pickup time set!');
    }

    public function addNote(Request $request, Order $order)
    {
        $this->authorize('update', $order);
        $request->validate(['note' => 'required|string']);
        $order->history = collect($order->history ?? [])->push([
            'actor' => 'provider',
            'action' => 'add_note',
            'note' => $request->note,
            'timestamp' => now(),
        ]);
        $order->save();
        return back()->with('success', 'Note added!');
    }
} 