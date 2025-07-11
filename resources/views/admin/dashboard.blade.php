@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow text-center">
        <div class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</div>
        <div class="text-gray-500">Total Users</div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow text-center">
        <div class="text-2xl font-bold text-gray-900">{{ $totalProviders }}</div>
        <div class="text-gray-500">Total Providers</div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow text-center">
        <div class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</div>
        <div class="text-gray-500">Total Orders</div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow text-center">
        <div class="text-2xl font-bold text-gray-900">₹{{ number_format($totalRevenue, 2) }}</div>
        <div class="text-gray-500">Total Revenue</div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white p-6 rounded-lg shadow mb-8">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.reports.index') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-blue-100 rounded">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Analytics Dashboard</p>
                    <p class="text-sm text-gray-500">Comprehensive overview</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.reports.sales') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-green-100 rounded">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Sales Report</p>
                    <p class="text-sm text-gray-500">Revenue analysis</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.reports.users') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-purple-100 rounded">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900">User Analytics</p>
                    <p class="text-sm text-gray-500">User behavior & growth</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.reports.orders') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-orange-100 rounded">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

<!-- Recent Activity -->
<div class="bg-white p-6 rounded-lg shadow">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h3>
    <div class="space-y-4">
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                <div>
                    <p class="font-medium text-gray-900">New Reports Available</p>
                    <p class="text-sm text-gray-500">Comprehensive analytics dashboard is now live</p>
                </div>
            </div>
            <span class="text-sm text-gray-500">Just now</span>
        </div>
        
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                <div>
                    <p class="font-medium text-gray-900">System Status</p>
                    <p class="text-sm text-gray-500">All systems operational</p>
                </div>
            </div>
            <span class="text-sm text-gray-500">2 hours ago</span>
        </div>
    </div>
</div>
@endsection 