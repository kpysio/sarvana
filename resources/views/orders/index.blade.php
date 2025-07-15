@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">My Orders</h1>
    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2">Order ID</th>
                    <th class="px-4 py-2">Food Item</th>
                    <th class="px-4 py-2">Provider</th>
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
                        <td class="border px-4 py-2">{{ $order->foodItem->provider->name ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $order->quantity }}</td>
                        <td class="border px-4 py-2">{{ ucfirst($order->status) }}</td>
                        <td class="border px-4 py-2">{{ $order->pickup_time ? \Carbon\Carbon::parse($order->pickup_time)->format('M d, Y g:i A') : 'Not set' }}</td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $orders->links() }}</div>
    </div>
</div>
@endsection 