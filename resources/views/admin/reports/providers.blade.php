@extends('layouts.admin')

@section('title', 'Provider Analytics')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Provider Analytics</h2>
            <p class="text-gray-600">Provider performance, growth, and marketplace insights</p>
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

<!-- Provider Overview Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Providers</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($providerStats['total_providers']) }}</p>
            </div>
            <div class="p-3 bg-orange-100 rounded-full">
                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm text-gray-500">New Providers</p>
            <p class="text-xl font-semibold text-gray-900">{{ number_format($providerStats['new_providers']) }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Active Providers</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($providerStats['active_providers']) }}</p>
            </div>
            <div class="p-3 bg-green-100 rounded-full">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm text-gray-500">Activity Rate</p>
            <p class="text-xl font-semibold text-gray-900">{{ number_format($providerStats['activity_rate'], 1) }}%</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Provider Growth</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($providerGrowth['current_providers']) }}</p>
            </div>
            <div class="p-3 bg-blue-100 rounded-full">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <span class="text-sm {{ $providerGrowth['growth_rate'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ $providerGrowth['growth_rate'] >= 0 ? '+' : '' }}{{ number_format($providerGrowth['growth_rate'], 1) }}%
            </span>
            <span class="text-sm text-gray-500">vs previous period</span>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Avg Revenue/Provider</p>
                <p class="text-3xl font-bold text-gray-900">₹{{ number_format($providerPerformance->avg('revenue') ?? 0, 2) }}</p>
            </div>
            <div class="p-3 bg-purple-100 rounded-full">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm text-gray-500">Top Performer</p>
            <p class="text-xl font-semibold text-gray-900">₹{{ number_format($providerPerformance->max('revenue') ?? 0, 2) }}</p>
        </div>
    </div>
</div>

<!-- Provider Performance Chart -->
<div class="bg-white p-6 rounded-lg shadow mb-8">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Provider Performance Distribution</h3>
    <div class="h-80 flex items-end justify-between space-x-2">
        @foreach($providerPerformance->take(10) as $provider)
        <div class="flex-1 flex flex-col items-center">
            <div class="w-full bg-gradient-to-t from-orange-600 to-orange-400 rounded-t" style="height: {{ $provider->revenue > 0 ? ($provider->revenue / $providerPerformance->max('revenue')) * 300 : 0 }}px"></div>
            <p class="text-xs text-gray-500 mt-2 text-center truncate w-full">{{ Str::limit($provider->name, 8) }}</p>
            <p class="text-xs font-medium text-gray-700 mt-1">₹{{ number_format($provider->revenue, 0) }}</p>
        </div>
        @endforeach
    </div>
</div>

<!-- Provider Analytics -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Top Performing Providers -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Performing Providers</h3>
        <div class="space-y-4">
            @foreach($providerPerformance->take(10) as $index => $provider)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                        <span class="text-sm font-bold text-orange-600">{{ $index + 1 }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $provider->name }}</p>
                        <p class="text-sm text-gray-500">{{ $provider->orders }} orders</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-900">₹{{ number_format($provider->revenue, 2) }}</p>
                    <p class="text-sm text-gray-500">₹{{ number_format($provider->avg_order_value, 2) }} avg</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Provider Metrics -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Provider Metrics</h3>
        <div class="space-y-6">
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700">Activity Rate</span>
                    <span class="text-sm font-semibold text-gray-900">{{ number_format($providerStats['activity_rate'], 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $providerStats['activity_rate'] }}%"></div>
                </div>
            </div>
            
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700">Growth Rate</span>
                    <span class="text-sm font-semibold text-gray-900">{{ number_format($providerGrowth['growth_rate'], 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(abs($providerGrowth['growth_rate']), 100) }}%"></div>
                </div>
            </div>
            
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700">Average Orders/Provider</span>
                    <span class="text-sm font-semibold text-gray-900">{{ number_format($providerPerformance->avg('orders'), 1) }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-purple-600 h-2 rounded-full" style="width: {{ min(($providerPerformance->avg('orders') / max($providerPerformance->max('orders'), 1)) * 100, 100) }}%"></div>
                </div>
            </div>
            
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700">Average Revenue/Provider</span>
                    <span class="text-sm font-semibold text-gray-900">₹{{ number_format($providerPerformance->avg('revenue'), 2) }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-orange-600 h-2 rounded-full" style="width: {{ min(($providerPerformance->avg('revenue') / max($providerPerformance->max('revenue'), 1)) * 100, 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Provider Insights -->
<div class="mt-8 bg-white p-6 rounded-lg shadow">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Provider Insights</h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="text-center p-4 bg-orange-50 rounded-lg">
            <p class="text-2xl font-bold text-orange-600">{{ number_format($providerStats['total_providers']) }}</p>
            <p class="text-sm text-gray-600">Total Providers</p>
        </div>
        <div class="text-center p-4 bg-green-50 rounded-lg">
            <p class="text-2xl font-bold text-green-600">{{ number_format($providerStats['active_providers']) }}</p>
            <p class="text-sm text-gray-600">Active Providers</p>
        </div>
        <div class="text-center p-4 bg-blue-50 rounded-lg">
            <p class="text-2xl font-bold text-blue-600">{{ number_format($providerStats['new_providers']) }}</p>
            <p class="text-sm text-gray-600">New Providers</p>
        </div>
        <div class="text-center p-4 bg-purple-50 rounded-lg">
            <p class="text-2xl font-bold text-purple-600">{{ number_format($providerPerformance->count()) }}</p>
            <p class="text-sm text-gray-600">Performing Providers</p>
        </div>
    </div>
</div>

<!-- Provider Growth Analysis -->
<div class="mt-8 bg-white p-6 rounded-lg shadow">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Provider Growth Analysis</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-4 border border-gray-200 rounded-lg">
            <h4 class="font-medium text-gray-900 mb-2">Growth Trends</h4>
            <p class="text-sm text-gray-600 mb-3">Provider acquisition and retention patterns</p>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Current Period</span>
                    <span class="text-sm font-medium">{{ number_format($providerGrowth['current_providers']) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Previous Period</span>
                    <span class="text-sm font-medium">{{ number_format($providerGrowth['previous_providers']) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Growth Rate</span>
                    <span class="text-sm font-medium {{ $providerGrowth['growth_rate'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $providerGrowth['growth_rate'] >= 0 ? '+' : '' }}{{ number_format($providerGrowth['growth_rate'], 1) }}%
                    </span>
                </div>
            </div>
        </div>
        
        <div class="p-4 border border-gray-200 rounded-lg">
            <h4 class="font-medium text-gray-900 mb-2">Performance Metrics</h4>
            <p class="text-sm text-gray-600 mb-3">Revenue and order performance</p>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Avg Revenue</span>
                    <span class="text-sm font-medium">₹{{ number_format($providerPerformance->avg('revenue'), 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Avg Orders</span>
                    <span class="text-sm font-medium">{{ number_format($providerPerformance->avg('orders'), 1) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Top Revenue</span>
                    <span class="text-sm font-medium">₹{{ number_format($providerPerformance->max('revenue'), 2) }}</span>
                </div>
            </div>
        </div>
        
        <div class="p-4 border border-gray-200 rounded-lg">
            <h4 class="font-medium text-gray-900 mb-2">Activity Analysis</h4>
            <p class="text-sm text-gray-600 mb-3">Provider engagement and activity</p>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Activity Rate</span>
                    <span class="text-sm font-medium">{{ number_format($providerStats['activity_rate'], 1) }}%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Active Providers</span>
                    <span class="text-sm font-medium">{{ number_format($providerStats['active_providers']) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Inactive</span>
                    <span class="text-sm font-medium">{{ number_format($providerStats['total_providers'] - $providerStats['active_providers']) }}</span>
                </div>
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