<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'provider', 'foodItem']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }
        
        // Filter by user type (customer/provider)
        if ($request->filled('user_type')) {
            if ($request->user_type === 'customer') {
                $query->whereHas('customer', function($q) {
                    $q->where('user_type', 'customer');
                });
            } elseif ($request->user_type === 'provider') {
                $query->whereHas('provider', function($q) {
                    $q->where('user_type', 'provider');
                });
            }
        }
        
        // Search by customer/provider name or food item
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('customer', function($customerQuery) use ($search) {
                    $customerQuery->where('name', 'like', "%{$search}%")
                                 ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('provider', function($providerQuery) use ($search) {
                    $providerQuery->where('name', 'like', "%{$search}%")
                                 ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('foodItem', function($foodQuery) use ($search) {
                    $foodQuery->where('title', 'like', "%{$search}%");
                });
            });
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
        $userTypes = ['customer', 'provider'];
        
        return view('admin.orders.index', compact('orders', 'statuses', 'userTypes'));
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'provider', 'foodItem', 'reviews']);
        
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $order->status;
        $order->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return back()->with('success', "Order status updated from '{$oldStatus}' to '{$request->status}'.");
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'orders' => 'required|array',
            'orders.*' => 'exists:orders,id',
        ]);

        $updated = Order::whereIn('id', $request->orders)
            ->update(['status' => $request->status]);

        return back()->with('success', "Updated status for {$updated} orders.");
    }

    public function export(Request $request)
    {
        $query = Order::with(['customer', 'provider', 'foodItem']);
        
        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }
        
        $orders = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'orders_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];
        
        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Order ID',
                'Customer Name',
                'Customer Email',
                'Provider Name',
                'Provider Email',
                'Food Item',
                'Quantity',
                'Total Amount',
                'Status',
                'Pickup Time',
                'Created At',
                'Notes'
            ]);
            
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->customer->name,
                    $order->customer->email,
                    $order->provider->name,
                    $order->provider->email,
                    $order->foodItem->title,
                    $order->quantity,
                    $order->total_amount,
                    $order->status,
                    $order->pickup_time,
                    $order->created_at,
                    $order->notes
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }

    public function analytics()
    {
        $totalOrders = Order::count();
        $completedOrders = Order::where('status', 'completed')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');
        
        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
        
        $ordersByMonth = Order::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count, SUM(total_amount) as revenue')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        $topProviders = Order::where('status', 'completed')
            ->join('users', 'orders.provider_id', '=', 'users.id')
            ->selectRaw('users.name, COUNT(orders.id) as orders, SUM(orders.total_amount) as revenue')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();
        
        return view('admin.orders.analytics', compact(
            'totalOrders',
            'completedOrders',
            'pendingOrders',
            'totalRevenue',
            'ordersByStatus',
            'ordersByMonth',
            'topProviders'
        ));
    }
} 