@extends('layouts.provider')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-purple-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 mb-1">Provider Dashboard</h1>
                <p class="text-gray-500 text-sm">Welcome, {{ Auth::user()->name }}! Here’s your business at a glance.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('provider.food-items.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg font-semibold shadow">Add New Item</a>
                <a href="{{ route('provider.orders.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-semibold shadow">View Orders</a>
                <a href="{{ route('provider.food-items.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2 rounded-lg font-semibold shadow">View Store</a>
            </div>
        </div>
        <!-- Analytics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 xl:grid-cols-5 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow flex flex-col items-center">
                <div class="p-3 bg-blue-100 rounded-full mb-2">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <div class="text-sm font-medium text-gray-600">Total Orders</div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($orderStats['total_orders']) }}</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow flex flex-col items-center">
                <div class="p-3 bg-green-100 rounded-full mb-2">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div class="text-sm font-medium text-gray-600">Completed</div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($orderStats['completed_orders']) }}</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow flex flex-col items-center">
                <div class="p-3 bg-yellow-100 rounded-full mb-2">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" /></svg>
                </div>
                <div class="text-sm font-medium text-gray-600">Pending</div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($orderStats['pending_orders']) }}</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow flex flex-col items-center">
                <div class="p-3 bg-red-100 rounded-full mb-2">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <div class="text-sm font-medium text-gray-600">Cancelled</div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($orderStats['cancelled_orders']) }}</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow flex flex-col items-center">
                <div class="p-3 bg-purple-100 rounded-full mb-2">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2" /></svg>
                </div>
                <div class="text-sm font-medium text-gray-600">Revenue</div>
                <div class="text-2xl font-bold text-gray-900">£{{ number_format($orderStats['total_revenue'], 2) }}</div>
            </div>
        </div>
        <!-- Latest Review Card -->
        <div class="bg-white p-6 rounded-lg shadow mb-8 max-w-xl">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Latest Review</h3>
            @php $latestReview = Auth::user()->providerReviews()->latest()->first(); @endphp
            @if($latestReview)
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-lg">
                        {{ strtoupper(substr($latestReview->reviewer->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900">{{ $latestReview->reviewer->name ?? 'User' }}</div>
                        <div class="text-xs text-gray-500">{{ $latestReview->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                <div class="flex items-center mb-2">
                    @for($i=1; $i<=5; $i++)
                        <svg class="w-4 h-4 {{ $i <= $latestReview->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.388-2.46a1 1 0 00-1.175 0l-3.388 2.46c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.045 9.394c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.967z" /></svg>
                    @endfor
                </div>
                <div class="text-gray-700 mb-2">{{ $latestReview->comment }}</div>
                <a href="{{ route('provider.reviews.index') }}" class="text-blue-600 hover:underline text-sm font-semibold">Show More</a>
            @else
                <div class="text-gray-500">No reviews yet.</div>
            @endif
        </div>
        <!-- Order Trend Chart -->
        <div class="bg-white p-6 rounded-lg shadow mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Trend (Last 14 Days)</h3>
            <div class="h-40 flex items-end justify-between space-x-2">
                @foreach($orderTrends as $trend)
                <div class="flex-1 flex flex-col items-center">
                    <div class="w-full bg-gradient-to-t from-blue-600 to-blue-400 rounded-t" style="height: {{ $trend['orders'] > 0 ? ($trend['orders'] / max($orderTrends->max('orders'),1)) * 120 : 0 }}px"></div>
                    <p class="text-xs text-gray-500 mt-2 text-center">{{ $trend['date'] }}</p>
                    <p class="text-xs font-medium text-gray-700 mt-1">{{ $trend['orders'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
        <!-- Most Popular Items -->
        <div class="bg-white p-6 rounded-lg shadow mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Most Popular Items</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach($popularItems as $item)
                <div class="flex flex-col items-center bg-gray-50 rounded-lg p-4 shadow-sm">
                    @if($item['photo'])
                        <img src="{{ Str::startsWith($item['photo'], ['http://', 'https://']) ? $item['photo'] : asset('storage/' . ltrim($item['photo'], '/')) }}" alt="{{ $item['title'] }}" class="w-16 h-16 object-cover rounded mb-2">
                    @else
                        <div class="w-16 h-16 bg-gray-200 flex items-center justify-center rounded text-gray-400 mb-2">No Photo</div>
                    @endif
                    <div class="font-semibold text-gray-800 text-center">{{ $item['title'] }}</div>
                    <div class="text-xs text-gray-500">{{ $item['orders'] }} orders</div>
                </div>
                @endforeach
            </div>
        </div>
        <!-- Inventory Analytics Table -->
        <div class="bg-white p-6 rounded-lg shadow mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Inventory Analytics</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ordered</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Remaining</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Order Rate</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($inventoryAnalytics as $item)
                        <tr>
                            <td class="px-4 py-2 flex items-center gap-2">
                                @if($item['photo'])
                                    <img src="{{ Str::startsWith($item['photo'], ['http://', 'https://']) ? $item['photo'] : asset('storage/' . ltrim($item['photo'], '/')) }}" alt="{{ $item['title'] }}" class="w-8 h-8 object-cover rounded">
                                @else
                                    <div class="w-8 h-8 bg-gray-200 flex items-center justify-center rounded text-gray-400">-</div>
                                @endif
                                <span class="font-semibold text-gray-800">{{ $item['title'] }}</span>
                            </td>
                            <td class="px-4 py-2">{{ $item['total'] }}</td>
                            <td class="px-4 py-2">{{ $item['ordered'] }}</td>
                            <td class="px-4 py-2">{{ $item['remaining'] }}</td>
                            <td class="px-4 py-2">{{ number_format($item['order_rate'], 1) }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Low Stock Alerts -->
        @if($lowStock->count())
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg shadow mb-8">
            <h3 class="text-md font-semibold text-yellow-800 mb-2">Low Stock Alerts</h3>
            <ul class="list-disc pl-6 text-yellow-700">
                @foreach($lowStock as $item)
                <li>{{ $item['title'] }}: Only {{ $item['remaining'] }} left!</li>
                @endforeach
            </ul>
        </div>
        @endif
        <!-- Ratings, Followers, Reviews -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow flex flex-col items-center">
                <div class="p-3 bg-yellow-100 rounded-full mb-2">
                    <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 17.75l-6.172 3.245 1.179-6.873-5-4.873 6.9-1.002L12 2.5l3.093 6.747 6.9 1.002-5 4.873 1.179 6.873z" /></svg>
                </div>
                <div class="text-sm font-medium text-gray-600">Avg. Rating</div>
                <div class="text-2xl font-bold text-gray-900">{{ $avgRating ? number_format($avgRating, 2) : 'N/A' }}</div>
                <div class="text-xs text-gray-500 mt-1">{{ $totalReviews }} reviews</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow flex flex-col items-center">
                <div class="p-3 bg-blue-100 rounded-full mb-2">
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 3.13a4 4 0 010 7.75M8 3.13a4 4 0 000 7.75" /></svg>
                </div>
                <div class="text-sm font-medium text-gray-600">Followers</div>
                <div class="text-2xl font-bold text-gray-900">{{ $followers }}</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow flex flex-col items-center">
                <div class="p-3 bg-green-100 rounded-full mb-2">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 10c-4.418 0-8-1.79-8-4V6a2 2 0 012-2h12a2 2 0 012 2v8c0 2.21-3.582 4-8 4z" /></svg>
                </div>
                <div class="text-sm font-medium text-gray-600">New Orders Today</div>
                <div class="text-2xl font-bold text-gray-900">{{ $orderStats['new_orders_today'] }}</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow flex flex-col items-center">
                <div class="p-3 bg-purple-100 rounded-full mb-2">
                    <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 10c-4.418 0-8-1.79-8-4V6a2 2 0 012-2h12a2 2 0 012 2v8c0 2.21-3.582 4-8 4z" /></svg>
                </div>
                <div class="text-sm font-medium text-gray-600">Completion Rate</div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($orderStats['completion_rate'], 1) }}%</div>
            </div>
        </div>
    </div>
</div>
@endsection 