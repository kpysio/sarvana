@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <a href="{{ route('provider.orders.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">&larr; Back to Orders</a>
    <div class="bg-white rounded shadow p-6 mb-6">
        <h1 class="text-xl font-bold mb-2">Order #{{ $order->id }}</h1>
        <div class="mb-2">Food Item: <span class="font-semibold">{{ $order->foodItem->title ?? '-' }}</span></div>
        <div class="mb-2">Customer: {{ $order->customer->name ?? '-' }}</div>
        <div class="mb-2">Quantity: {{ $order->quantity }}</div>
        <div class="mb-2">Status: <span class="font-semibold">{{ ucfirst($order->status) }}</span></div>
        <div class="mb-2">Pickup Time: {{ $order->pickup_time ? \Carbon\Carbon::parse($order->pickup_time)->format('M d, Y g:i A') : 'Not set' }}</div>
        <div class="mb-2">Special Instructions: {{ $order->customer_notes ?? '-' }}</div>
    </div>
    <div class="bg-white rounded shadow p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Order Timeline</h2>
        <ul class="timeline">
            @php $history = is_array($order->history) ? $order->history : json_decode($order->history, true) ?? []; @endphp
            <li><span class="font-bold">Order Placed</span> by Customer - {{ $order->created_at->format('M d, Y g:i A') }}</li>
            @foreach($history as $event)
                @if(is_array($event))
                    <li>
                        <span class="font-bold">{{ ucfirst($event['action'] ?? '') }}</span>
                        by {{ ucfirst($event['actor'] ?? '') }}
                        @if(isset($event['status'])) (Status: {{ ucfirst($event['status']) }}) @endif
                        @if(isset($event['note'])) (Note: {{ $event['note'] }}) @endif
                        - {{ isset($event['timestamp']) ? \Carbon\Carbon::parse($event['timestamp'])->format('M d, Y g:i A') : '' }}
                    </li>
                @else
                    <li>{{ $event }}</li>
                @endif
            @endforeach
        </ul>
    </div>
    <div class="bg-white rounded shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Provider Actions</h2>
        <form method="POST" action="{{ route('provider.orders.updateStatus', $order) }}" class="mb-4">
            @csrf
            <label class="block text-sm font-medium mb-1">Update Status</label>
            <select name="status" class="border rounded px-2 py-1" required>
                <option value="accepted" {{ $order->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                <option value="rejected" {{ $order->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready</option>
                <option value="collected" {{ $order->status == 'collected' ? 'selected' : '' }}>Collected</option>
                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button class="ml-2 bg-green-600 text-white px-3 py-1 rounded">Update</button>
        </form>
        <form method="POST" action="{{ route('provider.orders.addNote', $order) }}">
            @csrf
            <label class="block text-sm font-medium mb-1">Add Note</label>
            <input type="text" name="note" class="border rounded px-2 py-1 w-2/3" required>
            <button class="ml-2 bg-purple-600 text-white px-3 py-1 rounded">Add</button>
        </form>
    </div>
</div>
<script type="application/json" id="order-json">{!! $orderJson !!}</script>
<style>
.timeline { list-style: none; padding-left: 0; }
.timeline li { position: relative; padding-left: 1.5em; margin-bottom: 1em; }
.timeline li:before { content: ''; position: absolute; left: 0; top: 0.5em; width: 0.75em; height: 0.75em; background: #f59e42; border-radius: 50%; }
</style>
@endsection 