@extends('layouts.admin')

@section('title', 'User Analytics')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">User Analytics</h2>
            <p class="text-gray-600">User growth, demographics, and behavior analysis</p>
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

<!-- User Overview Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">New Users</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($userGrowth['current_users']) }}</p>
            </div>
            <div class="p-3 bg-green-100 rounded-full">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <span class="text-sm {{ $userGrowth['growth_rate'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ $userGrowth['growth_rate'] >= 0 ? '+' : '' }}{{ number_format($userGrowth['growth_rate'], 1) }}%
            </span>
            <span class="text-sm text-gray-500">vs previous period</span>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Active Users</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($activeUsers) }}</p>
            </div>
            <div class="p-3 bg-blue-100 rounded-full">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm text-gray-500">User Retention</p>
            <p class="text-xl font-semibold text-gray-900">{{ number_format($userRetention, 1) }}%</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Users</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($userTypes->sum()) }}</p>
            </div>
            <div class="p-3 bg-purple-100 rounded-full">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm text-gray-500">Platform Users</p>
            <p class="text-xl font-semibold text-gray-900">{{ number_format($userTypes->get('customer', 0) + $userTypes->get('provider', 0)) }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Conversion Rate</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format(($activeUsers / max($userGrowth['current_users'], 1)) * 100, 1) }}%</p>
            </div>
            <div class="p-3 bg-orange-100 rounded-full">
                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <p class="text-sm text-gray-500">New to Active</p>
            <p class="text-xl font-semibold text-gray-900">{{ number_format($activeUsers) }}</p>
        </div>
    </div>
</div>

<!-- User Demographics -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- User Types Distribution -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">User Types Distribution</h3>
        <div class="space-y-4">
            @php
                $totalUsers = $userTypes->sum();
            @endphp
            @foreach($userTypes as $type => $count)
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-4 h-4 rounded-full 
                        @if($type === 'customer') bg-blue-500
                        @elseif($type === 'provider') bg-orange-500
                        @else bg-gray-500
                        @endif"></div>
                    <span class="font-medium text-gray-900 capitalize">{{ $type }}</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-32 bg-gray-200 rounded-full h-2">
                        <div class="h-2 rounded-full 
                            @if($type === 'customer') bg-blue-500
                            @elseif($type === 'provider') bg-orange-500
                            @else bg-gray-500
                            @endif" 
                            style="width: {{ $totalUsers > 0 ? ($count / $totalUsers) * 100 : 0 }}%"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-900">{{ number_format($count) }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- User Growth Chart -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">User Growth Trend</h3>
        <div class="h-64 flex items-end justify-between space-x-2">
            @php
                $maxGrowth = max($userGrowth['current_users'], $userGrowth['previous_users'], 1);
            @endphp
            <div class="flex-1 flex flex-col items-center">
                <div class="w-full bg-green-200 rounded-t" style="height: {{ ($userGrowth['current_users'] / $maxGrowth) * 200 }}px"></div>
                <p class="text-xs text-gray-500 mt-2">Current</p>
                <p class="text-xs font-medium text-gray-700">{{ number_format($userGrowth['current_users']) }}</p>
            </div>
            <div class="flex-1 flex flex-col items-center">
                <div class="w-full bg-blue-200 rounded-t" style="height: {{ ($userGrowth['previous_users'] / $maxGrowth) * 200 }}px"></div>
                <p class="text-xs text-gray-500 mt-2">Previous</p>
                <p class="text-xs font-medium text-gray-700">{{ number_format($userGrowth['previous_users']) }}</p>
            </div>
        </div>
    </div>
</div>

<!-- User Activity Metrics -->
<div class="bg-white p-6 rounded-lg shadow mb-8">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">User Activity Metrics</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="text-center p-4 bg-gray-50 rounded-lg">
            <p class="text-2xl font-bold text-gray-900">{{ number_format($userRetention, 1) }}%</p>
            <p class="text-sm text-gray-500">Retention Rate</p>
        </div>
        <div class="text-center p-4 bg-gray-50 rounded-lg">
            <p class="text-2xl font-bold text-gray-900">{{ number_format($activeUsers) }}</p>
            <p class="text-sm text-gray-500">Active Users</p>
        </div>
        <div class="text-center p-4 bg-gray-50 rounded-lg">
            <p class="text-2xl font-bold text-gray-900">{{ number_format($userGrowth['growth_rate'], 1) }}%</p>
            <p class="text-sm text-gray-500">Growth Rate</p>
        </div>
    </div>
</div>

<!-- User Behavior Insights -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Top User Segments -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">User Segments</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                <div>
                    <p class="font-medium text-gray-900">New Users</p>
                    <p class="text-sm text-gray-500">Recently registered</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-900">{{ number_format($userGrowth['current_users']) }}</p>
                    <p class="text-sm text-blue-600">{{ number_format($userGrowth['growth_rate'], 1) }}% growth</p>
                </div>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                <div>
                    <p class="font-medium text-gray-900">Active Users</p>
                    <p class="text-sm text-gray-500">Made orders recently</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-900">{{ number_format($activeUsers) }}</p>
                    <p class="text-sm text-green-600">{{ number_format($userRetention, 1) }}% retention</p>
                </div>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                <div>
                    <p class="font-medium text-gray-900">Providers</p>
                    <p class="text-sm text-gray-500">Food item creators</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-900">{{ number_format($userTypes->get('provider', 0)) }}</p>
                    <p class="text-sm text-orange-600">{{ number_format(($userTypes->get('provider', 0) / max($userTypes->sum(), 1)) * 100, 1) }}% of total</p>
                </div>
            </div>
        </div>
    </div>

    <!-- User Engagement -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Engagement Metrics</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Daily Active Users</span>
                <span class="font-semibold text-gray-900">{{ number_format($activeUsers) }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(($activeUsers / max($userTypes->sum(), 1)) * 100, 100) }}%"></div>
            </div>
            
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">User Activation Rate</span>
                <span class="font-semibold text-gray-900">{{ number_format(($activeUsers / max($userGrowth['current_users'], 1)) * 100, 1) }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-green-600 h-2 rounded-full" style="width: {{ min(($activeUsers / max($userGrowth['current_users'], 1)) * 100, 100) }}%"></div>
            </div>
            
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Customer to Provider Ratio</span>
                <span class="font-semibold text-gray-900">{{ number_format($userTypes->get('customer', 0) / max($userTypes->get('provider', 1), 1), 1) }}:1</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-purple-600 h-2 rounded-full" style="width: {{ min(($userTypes->get('customer', 0) / max($userTypes->sum(), 1)) * 100, 100) }}%"></div>
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