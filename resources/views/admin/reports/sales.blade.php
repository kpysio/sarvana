@extends('layouts.admin')

@section('title', 'Sales Report')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Sales Report</h2>
            <p class="text-gray-600">Comprehensive revenue and sales analytics</p>
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

<!-- Sales Overview Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                <p class="text-3xl font-bold text-gray-900">₹{{ number_format($salesData['total_revenue'], 2) }}</p>
            </div>
            <div class="p-3 bg-green-100 rounded-full">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <span class="text-sm {{ $salesData['growth_rate'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ $salesData['growth_rate'] >= 0 ? '+' : '' }}{{ number_format($salesData['growth_rate'], 1) }}%
            </span>
            <span class="text-sm text-gray-500">vs previous period</span>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Orders</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($salesData['total_orders']) }}</p>
            </div>
            <div class="p-3 bg-blue-100 rounded-full">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm text-gray-500">Average Order Value</p>
            <p class="text-xl font-semibold text-gray-900">₹{{ number_format($salesData['average_order_value'], 2) }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Conversion Rate</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format(($salesData['total_orders'] / max($activeUsers, 1)) * 100, 1) }}%</p>
            </div>
            <div class="p-3 bg-purple-100 rounded-full">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm text-gray-500">Orders per Active User</p>
            <p class="text-xl font-semibold text-gray-900">{{ number_format($salesData['total_orders'] / max($activeUsers, 1), 1) }}</p>
        </div>
    </div>
</div>

<!-- Revenue Chart -->
<div class="bg-white p-6 rounded-lg shadow mb-8">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Revenue Trend</h3>
    <div class="h-80 flex items-end justify-between space-x-2">
        @foreach($revenueByMonth as $data)
        <div class="flex-1 flex flex-col items-center">
            <div class="w-full bg-gradient-to-t from-blue-600 to-blue-400 rounded-t" style="height: {{ $data['revenue'] > 0 ? ($data['revenue'] / $revenueByMonth->max('revenue')) * 300 : 0 }}px"></div>
            <p class="text-xs text-gray-500 mt-2 text-center">{{ $data['month'] }}</p>
            <p class="text-xs font-medium text-gray-700 mt-1">₹{{ number_format($data['revenue'], 0) }}</p>
        </div>
        @endforeach
    </div>
</div>

<!-- Top Providers and Sales by Category -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Top Providers -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Performing Providers</h3>
        <div class="space-y-4">
            @foreach($topProviders as $index => $provider)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                        <span class="text-sm font-bold text-orange-600">{{ $index + 1 }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $provider->name }}</p>
                        <p class="text-sm text-gray-500">{{ $provider->total_orders }} orders</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-900">₹{{ number_format($provider->total_revenue, 2) }}</p>
                    <p class="text-sm text-gray-500">₹{{ number_format($provider->total_revenue / max($provider->total_orders, 1), 2) }} avg</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Sales by Category -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Sales by Category</h3>
        <div class="space-y-4">
            @foreach($salesByCategory as $category)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-medium text-gray-900">{{ $category->name }}</p>
                    <p class="text-sm text-gray-500">{{ $category->orders }} orders</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-900">₹{{ number_format($category->revenue, 2) }}</p>
                    <p class="text-sm text-gray-500">{{ number_format(($category->revenue / max($salesData['total_revenue'], 1)) * 100, 1) }}%</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Detailed Metrics -->
<div class="mt-8 bg-white p-6 rounded-lg shadow">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Detailed Metrics</h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="text-center">
            <p class="text-2xl font-bold text-gray-900">₹{{ number_format($salesData['total_revenue'], 2) }}</p>
            <p class="text-sm text-gray-500">Total Revenue</p>
        </div>
        <div class="text-center">
            <p class="text-2xl font-bold text-gray-900">{{ number_format($salesData['total_orders']) }}</p>
            <p class="text-sm text-gray-500">Total Orders</p>
        </div>
        <div class="text-center">
            <p class="text-2xl font-bold text-gray-900">₹{{ number_format($salesData['average_order_value'], 2) }}</p>
            <p class="text-sm text-gray-500">Average Order Value</p>
        </div>
        <div class="text-center">
            <p class="text-2xl font-bold text-gray-900">{{ number_format($topProviders->count()) }}</p>
            <p class="text-sm text-gray-500">Active Providers</p>
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