@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">My Orders</h1>
    <!-- Filter Bar -->
    <form method="GET" class="mb-4 flex flex-col md:flex-row gap-2 md:gap-4 items-stretch md:items-end">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status" class="w-full border rounded px-2 py-1">
                <option value="">All</option>
                <option value="pending" @if(request('status')=='pending') selected @endif>Pending</option>
                <option value="accepted" @if(request('status')=='accepted') selected @endif>Accepted</option>
                <option value="rejected" @if(request('status')=='rejected') selected @endif>Rejected</option>
                <option value="preparing" @if(request('status')=='preparing') selected @endif>Preparing</option>
                <option value="ready" @if(request('status')=='ready') selected @endif>Ready</option>
                <option value="collected" @if(request('status')=='collected') selected @endif>Collected</option>
                <option value="completed" @if(request('status')=='completed') selected @endif>Completed</option>
                <option value="cancelled" @if(request('status')=='cancelled') selected @endif>Cancelled</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Food Item</label>
            <select name="food_item_id" class="w-full border rounded px-2 py-1">
                <option value="">All</option>
                @foreach(auth()->user()->foodItems as $item)
                    <option value="{{ $item->id }}" @if(request('food_item_id')==$item->id) selected @endif>{{ $item->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Order ID, Customer, etc." class="w-full border rounded px-2 py-1">
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded mt-2 md:mt-0 self-end">Filter</button>
    </form>
    <!-- Responsive Orders List -->
    <div class="hidden md:block bg-white rounded shadow overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2">Order ID</th>
                    <th class="px-4 py-2">Food Item</th>
                    <th class="px-4 py-2">Customer</th>
                    <th class="px-4 py-2">Quantity</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Pickup Time</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td class="border px-4 py-2">{{ $order->id }}</td>
                        <td class="border px-4 py-2">{{ $order->foodItem->title ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $order->customer->name ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $order->quantity }}</td>
                        <td class="border px-4 py-2">{{ ucfirst($order->status) }}</td>
                        <td class="border px-4 py-2">{{ $order->pickup_time ? \Carbon\Carbon::parse($order->pickup_time)->format('M d, Y g:i A') : 'Not set' }}</td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('provider.orders.show', $order) }}" class="text-blue-600 hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $orders->links() }}</div>
    </div>
    <!-- Mobile Card Layout -->
    <div class="md:hidden space-y-4">
        @forelse($orders as $order)
            <div class="bg-white rounded-lg shadow p-4 flex flex-col gap-2">
                <div class="flex justify-between items-center">
                    <div class="font-bold text-lg">#{{ $order->id }}</div>
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
                </div>
                <div class="text-gray-900 font-semibold">{{ $order->foodItem->title ?? '-' }}</div>
                <div class="text-sm text-gray-500">Customer: {{ $order->customer->name ?? '-' }}</div>
                <div class="text-sm text-gray-500">Quantity: {{ $order->quantity }}</div>
                <div class="text-sm text-gray-500">Pickup: {{ $order->pickup_time ? \Carbon\Carbon::parse($order->pickup_time)->format('M d, Y g:i A') : 'Not set' }}</div>
                <div class="flex gap-2 mt-2">
                    <a href="{{ route('provider.orders.show', $order) }}" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 rounded-lg font-semibold">View</a>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-400 py-12">No orders found.</div>
        @endforelse
        <div class="p-4">{{ $orders->links() }}</div>
    </div>
</div>
@endsection 