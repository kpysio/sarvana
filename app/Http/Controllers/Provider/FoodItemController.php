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
    public function index(Request $request)
    {
        $query = FoodItem::where('provider_id', Auth::id());

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by search (title or category)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('category', 'like', "%$search%") ;
            });
        }

        $items = $query->orderByDesc('created_at')->get();
        return view('provider.food-items.index', compact('items'));
    }

    // Show the form for creating a new food item
    public function create()
    {
        $tags = Tag::all();
        return view('provider.food-items.create', compact('tags'));
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
        return view('provider.food-items.edit', compact('foodItem', 'tags'));
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
        return view('provider.food-items.show', compact('foodItem', 'orders', 'orderStats', 'orderStatusBreakdown', 'inventoryBreakdown'));
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

    public function placeOrder(Request $request, FoodItem $foodItem)
    {
        $this->authorize('update', $foodItem); // Only provider can place order for their item
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:32',
            'quantity' => 'required|integer|min:1|max:' . $foodItem->available_quantity,
            'special_instruction' => 'nullable|string|max:500',
        ]);

        // Calculate total amount
        $totalAmount = $foodItem->price * $validated['quantity'];

        // Build customer notes
        $notes = 'Phone order: ' . $validated['customer_name'] . ' (' . $validated['customer_phone'] . ')';
        if (!empty($validated['special_instruction'])) {
            $notes .= ' | Instruction: ' . $validated['special_instruction'];
        }

        // Find or create the special phone order customer
        $phoneOrderCustomer = \App\Models\User::where('email', 'phoneorders@example.com')->first();
        if (!$phoneOrderCustomer) {
            // Fallback: create if not found
            $phoneOrderCustomer = \App\Models\User::create([
                'name' => 'Phone Order Customer',
                'email' => 'phoneorders@example.com',
                'password' => bcrypt('phoneorder123'),
                'user_type' => 'customer',
                'phone' => '0000000000',
            ]);
        }

        // Create the order (use phone order customer_id)
        $order = \App\Models\Order::create([
            'customer_id' => $phoneOrderCustomer->id,
            'provider_id' => $foodItem->provider_id,
            'food_item_id' => $foodItem->id,
            'quantity' => $validated['quantity'],
            'total_amount' => $totalAmount,
            'status' => \App\Models\Order::STATUS_PENDING,
            'customer_notes' => $notes,
        ]);

        // Reduce available quantity
        $foodItem->available_quantity -= $validated['quantity'];
        $foodItem->save();

        return redirect()->route('provider.food-items.show', $foodItem->id)
            ->with('success', 'Order placed successfully for customer: ' . $validated['customer_name']);
    }

    public function placeOrderForm(FoodItem $foodItem)
    {
        $this->authorize('update', $foodItem);
        return view('provider.food-items.place-order', compact('foodItem'));
    }
} 