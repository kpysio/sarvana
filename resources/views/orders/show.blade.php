@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <a href="{{ route('orders.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">&larr; Back to Orders</a>
    <div class="bg-white rounded shadow p-6 mb-6">
        <h1 class="text-xl font-bold mb-2">Order #{{ $order->id }}</h1>
        <div class="mb-2">Food Item: <span class="font-semibold">{{ $order->foodItem->title ?? '-' }}</span></div>
        <div class="mb-2">Provider: {{ $order->foodItem->provider->name ?? '-' }}</div>
        <div class="mb-2">Quantity: {{ $order->quantity }}</div>
        <div class="mb-2">Status: <span class="font-semibold">{{ ucfirst($order->status) }}</span></div>
        <div class="mb-2">Pickup Time: {{ $order->pickup_time ? \Carbon\Carbon::parse($order->pickup_time)->format('M d, Y g:i A') : 'Not set' }}</div>
        <div class="mb-2">Special Instructions: {{ $order->customer_notes ?? '-' }}</div>
    </div>
    <div class="bg-white rounded shadow p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Order Timeline</h2>
        <ul class="timeline" id="orderTimeline">
            @php 
                $history = is_array($order->history) ? $order->history : json_decode($order->history, true) ?? [];
                // Ensure $history is an array of arrays
                if (!is_array($history) || !array_is_list($history)) {
                    $history = [];
                } else {
                    // Remove any non-array elements
                    $history = array_filter($history, 'is_array');
                }
            @endphp
            <li><span class="font-bold">Order Placed</span> by You - {{ $order->created_at->format('M d, Y g:i A') }}</li>
            @foreach($history as $event)
                <li>
                    <span class="font-bold">{{ ucfirst($event['action']) }}</span>
                    by {{ ucfirst($event['actor']) }}
                    @if(isset($event['status'])) (Status: {{ ucfirst($event['status']) }}) @endif
                    @if(isset($event['pickup_time'])) (Pickup: {{ \Carbon\Carbon::parse($event['pickup_time'])->format('M d, Y g:i A') }}) @endif
                    @if(isset($event['note'])) (Note: {{ $event['note'] }}) @endif
                    - {{ \Carbon\Carbon::parse($event['timestamp'])->format('M d, Y g:i A') }}
                </li>
            @endforeach
        </ul>
    </div>
    <div class="bg-white rounded shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Order Status</h2>
        <div id="orderStatus" class="text-lg font-bold text-blue-600">{{ ucfirst($order->status) }}</div>
        <div id="pickupTime" class="mt-2 text-gray-700">Pickup Time: {{ $order->pickup_time ? \Carbon\Carbon::parse($order->pickup_time)->format('M d, Y g:i A') : 'Not set' }}</div>
    </div>
</div>
<script>
// Simple polling for real-time updates
setInterval(function() {
    fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            document.getElementById('orderStatus').textContent = doc.getElementById('orderStatus').textContent;
            document.getElementById('pickupTime').textContent = doc.getElementById('pickupTime').textContent;
            document.getElementById('orderTimeline').innerHTML = doc.getElementById('orderTimeline').innerHTML;
        });
}, 10000); // every 10 seconds
</script>
<style>
.timeline { list-style: none; padding-left: 0; }
.timeline li { position: relative; padding-left: 1.5em; margin-bottom: 1em; }
.timeline li:before { content: ''; position: absolute; left: 0; top: 0.5em; width: 0.75em; height: 0.75em; background: #f59e42; border-radius: 50%; }
</style>
@endsection 