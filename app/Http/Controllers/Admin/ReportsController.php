<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\FoodItem;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);
        
        // Sales Analytics
        $salesData = $this->getSalesData($startDate);
        $revenueByMonth = $this->getRevenueByMonth();
        $topProviders = $this->getTopProviders($startDate);
        
        // User Analytics
        $userGrowth = $this->getUserGrowth($startDate);
        $userTypes = $this->getUserTypes();
        $activeUsers = $this->getActiveUsers($startDate);
        $userRetention = $this->getUserRetention($startDate);
        
        // Order Analytics
        $orderStats = $this->getOrderStats($startDate);
        $orderStatusBreakdown = $this->getOrderStatusBreakdown();
        $popularItems = $this->getPopularItems($startDate);
        
        // Provider Analytics
        $providerStats = $this->getProviderStats($startDate);
        $providerPerformance = $this->getProviderPerformance($startDate);
        
        return view('admin.reports.index', compact(
            'period',
            'salesData',
            'revenueByMonth',
            'topProviders',
            'userGrowth',
            'userTypes',
            'activeUsers',
            'userRetention',
            'orderStats',
            'orderStatusBreakdown',
            'popularItems',
            'providerStats',
            'providerPerformance'
        ));
    }

    public function sales(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);
        
        $salesData = $this->getSalesData($startDate);
        $revenueByMonth = $this->getRevenueByMonth();
        $topProviders = $this->getTopProviders($startDate);
        $salesByCategory = $this->getSalesByCategory($startDate);
        $activeUsers = $this->getActiveUsers($startDate);
        
        return view('admin.reports.sales', compact(
            'period',
            'salesData',
            'revenueByMonth',
            'topProviders',
            'salesByCategory',
            'activeUsers'
        ));
    }

    public function users(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);
        
        $userGrowth = $this->getUserGrowth($startDate);
        $userTypes = $this->getUserTypes();
        $activeUsers = $this->getActiveUsers($startDate);
        $userRetention = $this->getUserRetention($startDate);
        
        return view('admin.reports.users', compact(
            'period',
            'userGrowth',
            'userTypes',
            'activeUsers',
            'userRetention'
        ));
    }

    public function providers(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);
        
        $providerStats = $this->getProviderStats($startDate);
        $providerPerformance = $this->getProviderPerformance($startDate);
        $topProviders = $this->getTopProviders($startDate);
        $providerGrowth = $this->getProviderGrowth($startDate);
        
        return view('admin.reports.providers', compact(
            'period',
            'providerStats',
            'providerPerformance',
            'topProviders',
            'providerGrowth'
        ));
    }

    public function orders(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);
        
        $orderStats = $this->getOrderStats($startDate);
        $orderStatusBreakdown = $this->getOrderStatusBreakdown();
        $popularItems = $this->getPopularItems($startDate);
        $orderTrends = $this->getOrderTrends($startDate);
        
        return view('admin.reports.orders', compact(
            'period',
            'orderStats',
            'orderStatusBreakdown',
            'popularItems',
            'orderTrends'
        ));
    }

    private function getStartDate($period)
    {
        return match($period) {
            'week' => Carbon::now()->subWeek(),
            'month' => Carbon::now()->subMonth(),
            'quarter' => Carbon::now()->subQuarter(),
            'year' => Carbon::now()->subYear(),
            default => Carbon::now()->subMonth(),
        };
    }

    private function getSalesData($startDate)
    {
        $totalRevenue = Order::where('status', 'completed')
            ->where('created_at', '>=', $startDate)
            ->sum('total_amount');
            
        $totalOrders = Order::where('status', 'completed')
            ->where('created_at', '>=', $startDate)
            ->count();
            
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        
        $previousPeriod = Carbon::parse($startDate)->subDays(Carbon::now()->diffInDays($startDate));
        $previousRevenue = Order::where('status', 'completed')
            ->whereBetween('created_at', [$previousPeriod, $startDate])
            ->sum('total_amount');
            
        $growthRate = $previousRevenue > 0 ? (($totalRevenue - $previousRevenue) / $previousRevenue) * 100 : 0;
        
        return [
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'average_order_value' => $averageOrderValue,
            'growth_rate' => $growthRate
        ];
    }

    private function getRevenueByMonth()
    {
        return Order::where('status', 'completed')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_amount) as revenue')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function($item) {
                return [
                    'month' => Carbon::parse($item->month . '-01')->format('M Y'),
                    'revenue' => $item->revenue
                ];
            });
    }

    private function getTopProviders($startDate)
    {
        return Order::where('orders.status', 'completed')
            ->where('orders.created_at', '>=', $startDate)
            ->join('food_items', 'orders.food_item_id', '=', 'food_items.id')
            ->join('users', 'food_items.provider_id', '=', 'users.id')
            ->selectRaw('users.name, users.id, SUM(orders.total_amount) as total_revenue, COUNT(orders.id) as total_orders')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();
    }

    private function getSalesByCategory($startDate)
    {
        return Order::where('orders.status', 'completed')
            ->where('orders.created_at', '>=', $startDate)
            ->join('food_items', 'orders.food_item_id', '=', 'food_items.id')
            ->selectRaw('food_items.category, SUM(orders.total_amount) as revenue, COUNT(orders.id) as orders')
            ->groupBy('food_items.category')
            ->orderByDesc('revenue')
            ->get();
    }

    private function getUserGrowth($startDate)
    {
        $currentUsers = User::where('created_at', '>=', $startDate)->count();
        $previousPeriod = Carbon::parse($startDate)->subDays(Carbon::now()->diffInDays($startDate));
        $previousUsers = User::whereBetween('created_at', [$previousPeriod, $startDate])->count();
        
        $growthRate = $previousUsers > 0 ? (($currentUsers - $previousUsers) / $previousUsers) * 100 : 0;
        
        return [
            'current_users' => $currentUsers,
            'previous_users' => $previousUsers,
            'growth_rate' => $growthRate
        ];
    }

    private function getUserTypes()
    {
        return User::selectRaw('user_type, COUNT(*) as count')
            ->groupBy('user_type')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->user_type => $item->count];
            });
    }

    private function getActiveUsers($startDate)
    {
        return Order::where('created_at', '>=', $startDate)
            ->distinct('customer_id')
            ->count('customer_id');
    }

    private function getUserRetention($startDate)
    {
        $totalUsers = User::count();
        $activeUsers = Order::where('created_at', '>=', $startDate)
            ->distinct('customer_id')
            ->count('customer_id');
            
        return $totalUsers > 0 ? ($activeUsers / $totalUsers) * 100 : 0;
    }

    private function getOrderStats($startDate)
    {
        $totalOrders = Order::where('created_at', '>=', $startDate)->count();
        $completedOrders = Order::where('status', 'completed')
            ->where('created_at', '>=', $startDate)
            ->count();
        $pendingOrders = Order::where('status', 'pending')
            ->where('created_at', '>=', $startDate)
            ->count();
        $cancelledOrders = Order::where('status', 'cancelled')
            ->where('created_at', '>=', $startDate)
            ->count();
            
        return [
            'total_orders' => $totalOrders,
            'completed_orders' => $completedOrders,
            'pending_orders' => $pendingOrders,
            'cancelled_orders' => $cancelledOrders,
            'completion_rate' => $totalOrders > 0 ? ($completedOrders / $totalOrders) * 100 : 0
        ];
    }

    private function getOrderStatusBreakdown()
    {
        return Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->status => $item->count];
            });
    }

    private function getPopularItems($startDate)
    {
        return Order::where('orders.created_at', '>=', $startDate)
            ->join('food_items', 'orders.food_item_id', '=', 'food_items.id')
            ->selectRaw('food_items.title as name, food_items.id, COUNT(orders.id) as order_count, SUM(orders.total_amount) as revenue')
            ->groupBy('food_items.id', 'food_items.title')
            ->orderByDesc('order_count')
            ->limit(10)
            ->get();
    }

    private function getOrderTrends($startDate)
    {
        return Order::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total_amount) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function($item) {
                return [
                    'date' => Carbon::parse($item->date)->format('M d'),
                    'orders' => $item->orders,
                    'revenue' => $item->revenue
                ];
            });
    }

    private function getProviderStats($startDate)
    {
        $totalProviders = User::where('user_type', 'provider')->count();
        $activeProviders = Order::where('orders.created_at', '>=', $startDate)
            ->join('food_items', 'orders.food_item_id', '=', 'food_items.id')
            ->distinct('food_items.provider_id')
            ->count('food_items.provider_id');
        $newProviders = User::where('user_type', 'provider')
            ->where('created_at', '>=', $startDate)
            ->count();
            
        return [
            'total_providers' => $totalProviders,
            'active_providers' => $activeProviders,
            'new_providers' => $newProviders,
            'activity_rate' => $totalProviders > 0 ? ($activeProviders / $totalProviders) * 100 : 0
        ];
    }

    private function getProviderPerformance($startDate)
    {
        return Order::where('orders.status', 'completed')
            ->where('orders.created_at', '>=', $startDate)
            ->join('food_items', 'orders.food_item_id', '=', 'food_items.id')
            ->join('users', 'food_items.provider_id', '=', 'users.id')
            ->selectRaw('users.name, users.id, COUNT(orders.id) as orders, SUM(orders.total_amount) as revenue, AVG(orders.total_amount) as avg_order_value')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('revenue')
            ->limit(20)
            ->get();
    }

    private function getProviderGrowth($startDate)
    {
        $currentProviders = User::where('user_type', 'provider')
            ->where('created_at', '>=', $startDate)
            ->count();
        $previousPeriod = Carbon::parse($startDate)->subDays(Carbon::now()->diffInDays($startDate));
        $previousProviders = User::where('user_type', 'provider')
            ->whereBetween('created_at', [$previousPeriod, $startDate])
            ->count();
            
        $growthRate = $previousProviders > 0 ? (($currentProviders - $previousProviders) / $previousProviders) * 100 : 0;
        
        return [
            'current_providers' => $currentProviders,
            'previous_providers' => $previousProviders,
            'growth_rate' => $growthRate
        ];
    }
} 