<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $provider = auth()->user();
        // Orders and Food Items
        $orders = $provider->providerOrders()->with('foodItem')->get();
        $foodItems = $provider->foodItems()->with('orders')->get();
        // Order Analytics
        $orderStats = [
            'total_orders' => $orders->count(),
            'completed_orders' => $orders->where('status', 'completed')->count(),
            'pending_orders' => $orders->where('status', 'pending')->count(),
            'cancelled_orders' => $orders->where('status', 'cancelled')->count(),
            'completion_rate' => $orders->count() ? ($orders->where('status', 'completed')->count() / $orders->count()) * 100 : 0,
            'total_revenue' => $orders->where('status', 'completed')->sum('total_amount'),
            'new_orders_today' => $orders->where('created_at', '>=', now()->startOfDay())->count(),
        ];
        // Order Trends (last 14 days)
        $orderTrends = collect(range(0, 13))->map(function($i) use ($orders) {
            $date = now()->subDays(13 - $i)->startOfDay();
            $count = $orders->whereBetween('created_at', [$date, $date->copy()->endOfDay()])->count();
            return [
                'date' => $date->format('M d'),
                'orders' => $count,
            ];
        });
        // Most Popular Items (by order count)
        $popularItems = $foodItems->map(function($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'photo' => is_array($item->photos) && count($item->photos) ? $item->photos[0] : null,
                'orders' => $item->orders->count(),
            ];
        })->sortByDesc('orders')->take(5);
        // Inventory Analytics per Food Item
        $inventoryAnalytics = $foodItems->map(function($item) {
            $ordered = $item->orders->sum('quantity');
            $total = $item->available_quantity + $ordered;
            $rate = $total > 0 ? ($ordered / $total) * 100 : 0;
            return [
                'id' => $item->id,
                'title' => $item->title,
                'photo' => is_array($item->photos) && count($item->photos) ? $item->photos[0] : null,
                'total' => $total,
                'ordered' => $ordered,
                'remaining' => $item->available_quantity,
                'order_rate' => $rate,
            ];
        });
        // Low Stock Alerts
        $lowStock = $inventoryAnalytics->filter(fn($i) => $i['remaining'] <= 5 && $i['total'] > 0);
        // Average Rating
        $avgRating = $provider->providerReviews()->avg('rating');
        $totalReviews = $provider->providerReviews()->count();
        // Followers
        $followers = $provider->followers()->count();
        return view('provider.dashboard', compact(
            'orderStats', 'orderTrends', 'popularItems', 'inventoryAnalytics', 'lowStock', 'avgRating', 'totalReviews', 'followers', 'foodItems'
        ));
    }
} 