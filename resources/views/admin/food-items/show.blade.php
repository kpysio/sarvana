@extends('layouts.admin')

@section('title', 'Food Item Details')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.food-items.index') }}" class="text-blue-600 hover:underline">&larr; Back to Food Items</a>
</div>
<div class="bg-white rounded shadow p-6 mb-6">
    <h1 class="text-2xl font-bold mb-2">{{ $foodItem->title }}</h1>
    <div class="mb-2">Provider: <span class="font-semibold">{{ $foodItem->provider->name ?? '-' }}</span></div>
    <div class="mb-2">Available Date: {{ $foodItem->available_date }}</div>
    <div class="mb-2">Status: <span class="font-semibold">{{ ucfirst($foodItem->status) }}</span></div>
    <div class="mb-2">Available Quantity: {{ $foodItem->available_quantity }}</div>
    <div class="mb-2">Orders: {{ $foodItem->orders->count() }}</div>
    <div class="mb-2">Tags: @foreach($foodItem->tags as $tag)<span class="inline-block bg-gray-200 rounded px-2 py-1 mr-1">{{ $tag->name }}</span>@endforeach</div>
    <div class="mb-2">Description: <br>{{ $foodItem->description }}</div>
</div>
<div class="bg-white rounded shadow p-6">
    <h2 class="text-xl font-semibold mb-4">Orders for this Item</h2>
    <table class="min-w-full mb-4">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2">Order ID</th>
                <th class="px-4 py-2">Customer</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($foodItem->orders as $order)
                <tr>
                    <td class="border px-4 py-2">{{ $order->id }}</td>
                    <td class="border px-4 py-2">{{ $order->customer->name ?? '-' }}</td>
                    <td class="border px-4 py-2">{{ ucfirst($order->status) }}</td>
                    <td class="border px-4 py-2">{{ $order->created_at }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center py-4">No orders for this item.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection 