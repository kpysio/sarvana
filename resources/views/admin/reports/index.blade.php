@extends('layouts.admin')

@section('title', 'Admin Reports')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Analytics Dashboard</h2>
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

<!-- Sales Overview -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                <p class="text-2xl font-bold text-gray-900">₹{{ number_format($salesData['total_revenue'], 2) }}</p>
            </div>
            <div class="p-2 bg-green-100 rounded-full">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <p class="text-2xl font-bold text-gray-900">{{ number_format($salesData['total_orders']) }}</p>
            </div>
            <div class="p-2 bg-blue-100 rounded-full">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm text-gray-500">Average Order Value</p>
            <p class="text-lg font-semibold text-gray-900">₹{{ number_format($salesData['average_order_value'], 2) }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Active Users</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($activeUsers) }}</p>
            </div>
            <div class="p-2 bg-purple-100 rounded-full">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm text-gray-500">User Retention</p>
            <p class="text-lg font-semibold text-gray-900">{{ number_format($userRetention, 1) }}%</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Active Providers</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($providerStats['active_providers']) }}</p>
            </div>
            <div class="p-2 bg-orange-100 rounded-full">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm text-gray-500">Activity Rate</p>
            <p class="text-lg font-semibold text-gray-900">{{ number_format($providerStats['activity_rate'], 1) }}%</p>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Revenue Chart -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Revenue Trend</h3>
        <div class="h-64 flex items-end justify-between space-x-2">
            @foreach($revenueByMonth as $data)
            <div class="flex-1 flex flex-col items-center">
                <div class="w-full bg-blue-200 rounded-t" style="height: {{ $data['revenue'] > 0 ? ($data['revenue'] / $revenueByMonth->max('revenue')) * 200 : 0 }}px"></div>
                <p class="text-xs text-gray-500 mt-2">{{ $data['month'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- User Growth Chart -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">User Growth</h3>
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">New Users</span>
                <span class="font-semibold">{{ number_format($userGrowth['current_users']) }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-green-600 h-2 rounded-full" style="width: {{ min(($userGrowth['current_users'] / max($userGrowth['previous_users'], 1)) * 100, 100) }}%"></div>
            </div>
            <div class="text-sm text-gray-500">
                Growth: {{ $userGrowth['growth_rate'] >= 0 ? '+' : '' }}{{ number_format($userGrowth['growth_rate'], 1) }}%
            </div>
        </div>
    </div>
</div>

<!-- Top Performers -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Top Providers -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Providers</h3>
        <div class="space-y-3">
            @foreach($topProviders->take(5) as $provider)
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                <div>
                    <p class="font-medium text-gray-900">{{ $provider->name }}</p>
                    <p class="text-sm text-gray-500">{{ $provider->total_orders }} orders</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-900">₹{{ number_format($provider->total_revenue, 2) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Popular Items -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Popular Items</h3>
        <div class="space-y-3">
            @foreach($popularItems->take(5) as $item)
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                <div>
                    <p class="font-medium text-gray-900">{{ $item->name }}</p>
                    <p class="text-sm text-gray-500">{{ $item->order_count }} orders</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-900">₹{{ number_format($item->revenue, 2) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-8 bg-white p-6 rounded-lg shadow">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.reports.sales') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-green-100 rounded">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Sales Report</p>
                    <p class="text-sm text-gray-500">Detailed revenue analysis</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.reports.users') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-blue-100 rounded">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900">User Analytics</p>
                    <p class="text-sm text-gray-500">User growth & behavior</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.reports.providers') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-orange-100 rounded">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Provider Performance</p>
                    <p class="text-sm text-gray-500">Provider analytics</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.reports.orders') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-purple-100 rounded">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Order Analytics</p>
                    <p class="text-sm text-gray-500">Order trends & status</p>
                </div>
            </div>
        </a>
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