<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\FoodItem;
use App\Models\Tag;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalUsers = User::count();
        $totalProviders = User::where('user_type', 'provider')->count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');
        return view('admin.dashboard', compact('totalUsers', 'totalProviders', 'totalOrders', 'totalRevenue'));
    }
} 