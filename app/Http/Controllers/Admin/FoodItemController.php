<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FoodItem;
use App\Models\Category;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FoodItemController extends Controller
{
    // Admin food item management dashboard
    public function index(Request $request)
    {
        // Filters
        $today = Carbon::today()->toDateString();
        $query = FoodItem::with('provider', 'tags', 'orders');

        // Due today
        if ($request->get('due') === 'today') {
            $query->where('available_date', $today);
        }
        // Live status
        if ($request->get('status') === 'live') {
            $query->where('status', 'active');
        }
        // 50%+ orders
        if ($request->get('half_full')) {
            $query->whereHas('orders', function($q) {
                $q->selectRaw('food_item_id, COUNT(*) as order_count')
                  ->groupBy('food_item_id')
                  ->havingRaw('order_count >= available_quantity / 2');
            });
        }
        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        // Popular items/categories (top 5 by orders)
        $popularItems = FoodItem::withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->take(5)->get();
        $popularCategories = FoodItem::select('category', DB::raw('COUNT(orders.id) as orders_count'))
            ->join('orders', 'food_items.id', '=', 'orders.food_item_id')
            ->groupBy('category')
            ->orderByDesc('orders_count')
            ->take(5)
            ->get();

        $foodItems = $query->latest()->paginate(20);
        $categories = Category::all();

        return view('admin.food-items.index', compact('foodItems', 'categories', 'popularItems', 'popularCategories'));
    }

    // Show food item details
    public function show(FoodItem $foodItem)
    {
        $foodItem->load('provider', 'tags', 'orders');
        return view('admin.food-items.show', compact('foodItem'));
    }
} 