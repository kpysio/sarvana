@extends('layouts.admin')

@section('title', 'Order Analytics')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Order Analytics</h2>
            <p class="text-gray-600">Order trends, status analysis, and fulfillment insights</p>
        </div>
        <div class="flex space-x-2">
            <select id="period" class="border border-gray-300 rounded-md px-3 py-2">
                <option value="week" {{ $period === 'week' ? 'selected' : '' }}>Last Week</option>
                <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Last Month</option>
                <option value="quarter" {{ $period === 'quarter' ? 'selected' : '' }}>Last Quarter</option>
                <option value="year" {{ $period === 'year' ? 'selected' : '' }}>Last Year</option>
            </select>
        </div>
    </div>
</div>

<!-- Order Overview Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Orders</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($orderStats['total_orders']) }}</p>
            </div>
            <div class="p-3 bg-blue-100 rounded-full">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm text-gray-500">Completion Rate</p>
            <p class="text-xl font-semibold text-gray-900">{{ number_format($orderStats['completion_rate'], 1) }}%</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Completed Orders</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($orderStats['completed_orders']) }}</p>
            </div>
            <div class="p-3 bg-green-100 rounded-full">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm text-gray-500">Success Rate</p>
            <p class="text-xl font-semibold text-gray-900">{{ number_format(($orderStats['completed_orders'] / max($orderStats['total_orders'], 1)) * 100, 1) }}%</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Pending Orders</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($orderStats['pending_orders']) }}</p>
            </div>
            <div class="p-3 bg-yellow-100 rounded-full">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm text-gray-500">Pending Rate</p>
            <p class="text-xl font-semibold text-gray-900">{{ number_format(($orderStats['pending_orders'] / max($orderStats['total_orders'], 1)) * 100, 1) }}%</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Cancelled Orders</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($orderStats['cancelled_orders']) }}</p>
            </div>
            <div class="p-3 bg-red-100 rounded-full">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm text-gray-500">Cancellation Rate</p>
            <p class="text-xl font-semibold text-gray-900">{{ number_format(($orderStats['cancelled_orders'] / max($orderStats['total_orders'], 1)) * 100, 1) }}%</p>
        </div>
    </div>
</div>

<!-- Order Status Breakdown -->
<div class="bg-white p-6 rounded-lg shadow mb-8">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Status Breakdown</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $totalOrders = $orderStatusBreakdown->sum();
        @endphp
        @foreach($orderStatusBreakdown as $status => $count)
        <div class="text-center p-4 rounded-lg 
            @if($status === 'completed') bg-green-50 border border-green-200
            @elseif($status === 'pending') bg-yellow-50 border border-yellow-200
            @elseif($status === 'cancelled') bg-red-50 border border-red-200
            @else bg-gray-50 border border-gray-200
            @endif">
            <div class="text-2xl font-bold 
                @if($status === 'completed') text-green-600
                @elseif($status === 'pending') text-yellow-600
                @elseif($status === 'cancelled') text-red-600
                @else text-gray-600
                @endif">
                {{ number_format($count) }}
            </div>
            <div class="text-sm font-medium text-gray-700 capitalize">{{ $status }}</div>
            <div class="text-xs text-gray-500">{{ number_format(($count / max($totalOrders, 1)) * 100, 1) }}%</div>
        </div>
        @endforeach
    </div>
</div>

<!-- Order Trends and Popular Items -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Order Trends Chart -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Trends</h3>
        <div class="h-64 flex items-end justify-between space-x-2">
            @foreach($orderTrends as $trend)
            <div class="flex-1 flex flex-col items-center">
                <div class="w-full bg-gradient-to-t from-blue-600 to-blue-400 rounded-t" style="height: {{ $trend['orders'] > 0 ? ($trend['orders'] / $orderTrends->max('orders')) * 200 : 0 }}px"></div>
                <p class="text-xs text-gray-500 mt-2 text-center">{{ $trend['date'] }}</p>
                <p class="text-xs font-medium text-gray-700 mt-1">{{ $trend['orders'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Popular Items -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Most Popular Items</h3>
        <div class="space-y-4">
            @foreach($popularItems->take(8) as $index => $item)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="w-6 h-6 bg-orange-100 rounded-full flex items-center justify-center">
                        <span class="text-xs font-bold text-orange-600">{{ $index + 1 }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ Str::limit($item->name, 25) }}</p>
                        <p class="text-sm text-gray-500">{{ $item->order_count }} orders</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-900">₹{{ number_format($item->revenue, 2) }}</p>
                    <p class="text-sm text-gray-500">₹{{ number_format($item->revenue / max($item->order_count, 1), 2) }} avg</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Order Analytics Details -->
<div class="mt-8 bg-white p-6 rounded-lg shadow">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Analytics Details</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-4 border border-gray-200 rounded-lg">
            <h4 class="font-medium text-gray-900 mb-2">Order Volume</h4>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Total Orders</span>
                    <span class="text-sm font-medium">{{ number_format($orderStats['total_orders']) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Completed</span>
                    <span class="text-sm font-medium text-green-600">{{ number_format($orderStats['completed_orders']) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Pending</span>
                    <span class="text-sm font-medium text-yellow-600">{{ number_format($orderStats['pending_orders']) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Cancelled</span>
                    <span class="text-sm font-medium text-red-600">{{ number_format($orderStats['cancelled_orders']) }}</span>
                </div>
            </div>
        </div>
        
        <div class="p-4 border border-gray-200 rounded-lg">
            <h4 class="font-medium text-gray-900 mb-2">Success Metrics</h4>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Completion Rate</span>
                    <span class="text-sm font-medium">{{ number_format($orderStats['completion_rate'], 1) }}%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Success Rate</span>
                    <span class="text-sm font-medium">{{ number_format(($orderStats['completed_orders'] / max($orderStats['total_orders'], 1)) * 100, 1) }}%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Cancellation Rate</span>
                    <span class="text-sm font-medium">{{ number_format(($orderStats['cancelled_orders'] / max($orderStats['total_orders'], 1)) * 100, 1) }}%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Pending Rate</span>
                    <span class="text-sm font-medium">{{ number_format(($orderStats['pending_orders'] / max($orderStats['total_orders'], 1)) * 100, 1) }}%</span>
                </div>
            </div>
        </div>
        
        <div class="p-4 border border-gray-200 rounded-lg">
            <h4 class="font-medium text-gray-900 mb-2">Trend Analysis</h4>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Daily Average</span>
                    <span class="text-sm font-medium">{{ number_format($orderTrends->avg('orders'), 1) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Peak Day</span>
                    <span class="text-sm font-medium">{{ $orderTrends->max('orders') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Total Revenue</span>
                    <span class="text-sm font-medium">₹{{ number_format($orderTrends->sum('revenue'), 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Avg Order Value</span>
                    <span class="text-sm font-medium">₹{{ number_format($orderTrends->sum('revenue') / max($orderTrends->sum('orders'), 1), 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Status Timeline -->
<div class="mt-8 bg-white p-6 rounded-lg shadow">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Status Timeline</h3>
    <div class="space-y-4">
        <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
            <div class="flex items-center space-x-3">
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <span class="font-medium text-gray-900">Completed Orders</span>
            </div>
            <div class="text-right">
                <p class="font-semibold text-gray-900">{{ number_format($orderStats['completed_orders']) }}</p>
                <p class="text-sm text-green-600">{{ number_format(($orderStats['completed_orders'] / max($orderStats['total_orders'], 1)) * 100, 1) }}% of total</p>
            </div>
        </div>
        
        <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg">
            <div class="flex items-center space-x-3">
                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                <span class="font-medium text-gray-900">Pending Orders</span>
            </div>
            <div class="text-right">
                <p class="font-semibold text-gray-900">{{ number_format($orderStats['pending_orders']) }}</p>
                <p class="text-sm text-yellow-600">{{ number_format(($orderStats['pending_orders'] / max($orderStats['total_orders'], 1)) * 100, 1) }}% of total</p>
            </div>
        </div>
        
        <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg">
            <div class="flex items-center space-x-3">
                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                <span class="font-medium text-gray-900">Cancelled Orders</span>
            </div>
            <div class="text-right">
                <p class="font-semibold text-gray-900">{{ number_format($orderStats['cancelled_orders']) }}</p>
                <p class="text-sm text-red-600">{{ number_format(($orderStats['cancelled_orders'] / max($orderStats['total_orders'], 1)) * 100, 1) }}% of total</p>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('period').addEventListener('change', function() {
    const url = new URL(window.location);
    url.searchParams.set('period', this.value);
    window.location.href = url.toString();
});
</script>
@endsection 