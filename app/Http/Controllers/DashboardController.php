<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FoodItem;
use App\Models\Order;

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user type.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->isProvider()) {
            return $this->providerDashboard($user);
        } else {
            return $this->customerDashboard($user);
        }
    }

    /**
     * Provider dashboard view.
     */
    private function providerDashboard($user)
    {
        $foodItems = $user->foodItems()->latest()->take(5)->get();
        $recentOrders = $user->providerOrders()->with(['customer', 'foodItem'])->latest()->take(5)->get();
        $totalRevenue = $user->providerOrders()->where('status', 'completed')->sum('total_amount');
        $pendingOrders = $user->providerOrders()->pending()->count();

        return view('dashboard.provider', compact('foodItems', 'recentOrders', 'totalRevenue', 'pendingOrders'));
    }

    /**
     * Customer dashboard view.
     */
    private function customerDashboard($user)
    {
        $activeOrders = $user->customerOrders()
            ->whereIn('status', ['pending', 'accepted', 'preparing', 'ready', 'collected'])
            ->with(['provider', 'foodItem'])
            ->latest()
            ->get();

        $orderHistory = $user->customerOrders()
            ->whereIn('status', ['completed', 'cancelled', 'rejected'])
            ->with(['provider', 'foodItem'])
            ->latest()
            ->get();

        // Guess: favorites is a relation on user, fallback to empty if not present
        $favorites = method_exists($user, 'favorites')
            ? $user->favorites()->with(['provider'])->get()
            : collect();

        return view('dashboard.customer', compact('activeOrders', 'orderHistory', 'favorites'));
    }
}
