@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Quick Actions -->
        <div class="flex flex-wrap gap-4 mb-8">
            <a href="{{ route('search.index') }}" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg font-semibold shadow">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v16a1 1 0 01-1 1H4a1 1 0 01-1-1V4z"/></svg>
                Browse Food
            </a>
            <a href="#favorites" class="flex items-center gap-2 bg-pink-500 hover:bg-pink-600 text-white px-5 py-3 rounded-lg font-semibold shadow">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                Favorites
            </a>
            <a href="#order-history" class="flex items-center gap-2 bg-gray-700 hover:bg-gray-800 text-white px-5 py-3 rounded-lg font-semibold shadow">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/><circle cx="12" cy="12" r="10"/></svg>
                Order History
            </a>
        </div>

        <!-- Active Orders -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Active Orders</h2>
            @if($activeOrders->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($activeOrders as $order)
                        <div class="bg-white rounded-xl shadow p-5 flex flex-col gap-3 relative">
                            <div class="flex gap-3 items-center">
                                @if($order->foodItem->photos && is_array($order->foodItem->photos) && count($order->foodItem->photos) > 0)
                                    <img src="{{ $order->foodItem->photos[0] }}" alt="{{ $order->foodItem->title }}" class="w-20 h-20 object-cover rounded-lg">
                                @else
                                    <div class="w-20 h-20 bg-gray-200 flex items-center justify-center rounded-lg text-gray-400">No Photo</div>
                                @endif
                                <div>
                                    <div class="font-semibold text-lg text-gray-900">{{ $order->foodItem->title }}</div>
                                    <div class="text-sm text-gray-500">From {{ $order->provider->name }}</div>
                                    <div class="text-xs text-gray-400">Pickup: {{ $order->pickup_time->format('M d, Y g:i A') }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'accepted') bg-blue-100 text-blue-800
                                    @elseif($order->status === 'preparing') bg-orange-100 text-orange-800
                                    @elseif($order->status === 'ready') bg-green-100 text-green-800
                                    @elseif($order->status === 'collected') bg-indigo-100 text-indigo-800
                                    @elseif($order->status === 'completed') bg-gray-100 text-gray-800
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                    @elseif($order->status === 'rejected') bg-red-200 text-red-900
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                                <span class="text-green-600 font-bold ml-auto">₹{{ $order->total_amount }}</span>
                            </div>
                            <!-- Progress Bar -->
                            <div class="w-full h-2 bg-gray-100 rounded mt-2">
                                @php
                                    $steps = ['pending','accepted','preparing','ready','collected','completed'];
                                    $current = array_search($order->status, $steps);
                                    $percent = $current !== false ? ($current / (count($steps)-1)) * 100 : 0;
                                @endphp
                                <div class="h-2 rounded bg-orange-400 transition-all" style="width: {{ $percent }}%"></div>
                            </div>
                            <div class="flex gap-2 mt-3">
                                <a href="{{ route('orders.show', $order) }}" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 rounded-lg font-semibold">Track</a>
                                @if($order->status === 'ready' && $order->proof_photo)
                                    <a href="{{ asset('storage/' . $order->proof_photo) }}" target="_blank" class="flex-1 bg-green-500 hover:bg-green-600 text-white text-center py-2 rounded-lg font-semibold">View Proof</a>
                                @endif
                                <a href="{{ route('food-items.show', $order->foodItem) }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 text-center py-2 rounded-lg font-semibold">Order Again</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-400 py-12">No active orders. <a href="{{ route('search.index') }}" class="text-blue-600 hover:text-blue-800">Order now</a></div>
            @endif
        </div>

        <!-- Order History -->
        <div id="order-history" class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Order History</h2>
            @if($orderHistory->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($orderHistory as $order)
                        <div class="bg-white rounded-xl shadow p-5 flex flex-col gap-3 relative">
                            <div class="flex gap-3 items-center">
                                @if($order->foodItem->photos && is_array($order->foodItem->photos) && count($order->foodItem->photos) > 0)
                                    <img src="{{ $order->foodItem->photos[0] }}" alt="{{ $order->foodItem->title }}" class="w-20 h-20 object-cover rounded-lg">
                                @else
                                    <div class="w-20 h-20 bg-gray-200 flex items-center justify-center rounded-lg text-gray-400">No Photo</div>
                                @endif
                                <div>
                                    <div class="font-semibold text-lg text-gray-900">{{ $order->foodItem->title }}</div>
                                    <div class="text-sm text-gray-500">From {{ $order->provider->name }}</div>
                                    <div class="text-xs text-gray-400">Pickup: {{ $order->pickup_time->format('M d, Y g:i A') }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($order->status === 'completed') bg-gray-100 text-gray-800
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                    @elseif($order->status === 'rejected') bg-red-200 text-red-900
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                                <span class="text-green-600 font-bold ml-auto">₹{{ $order->total_amount }}</span>
                            </div>
                            <div class="flex gap-2 mt-3">
                                <a href="{{ route('food-items.show', $order->foodItem) }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 text-center py-2 rounded-lg font-semibold">Order Again</a>
                                <a href="#" class="flex-1 bg-yellow-400 hover:bg-yellow-500 text-white text-center py-2 rounded-lg font-semibold">Rate</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-400 py-12">No order history yet.</div>
            @endif
        </div>

        <!-- Favorites & Quick Reorder -->
        <div id="favorites" class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Favorites & Quick Reorder</h2>
            @if($favorites->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($favorites as $item)
                        <div class="bg-white rounded-xl shadow p-5 flex flex-col gap-3 relative">
                            <div class="flex gap-3 items-center">
                                @if($item->photos && is_array($item->photos) && count($item->photos) > 0)
                                    <img src="{{ $item->photos[0] }}" alt="{{ $item->title }}" class="w-20 h-20 object-cover rounded-lg">
                                @else
                                    <div class="w-20 h-20 bg-gray-200 flex items-center justify-center rounded-lg text-gray-400">No Photo</div>
                                @endif
                                <div>
                                    <div class="font-semibold text-lg text-gray-900">{{ $item->title }}</div>
                                    <div class="text-sm text-gray-500">by {{ $item->provider->name }}</div>
                                    <div class="text-xs text-gray-400">₹{{ $item->price }}</div>
                                </div>
                            </div>
                            <div class="flex gap-2 mt-3">
                                <a href="{{ route('food-items.show', $item) }}" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 rounded-lg font-semibold">Order Again</a>
                                <a href="{{ route('food-items.show', $item) }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 text-center py-2 rounded-lg font-semibold">View</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-400 py-12">No favorites yet. <a href="{{ route('search.index') }}" class="text-blue-600 hover:text-blue-800">Browse food</a></div>
            @endif
        </div>
    </div>
</div>
@endsection 