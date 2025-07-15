@extends('layouts.provider')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Food Item Details</h2>
            <p class="text-gray-600">{{ $foodItem->title }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('provider.food-items.edit', $foodItem) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Edit Item</a>
            <a href="{{ route('provider.food-items.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Back to My Store</a>
        </div>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Food Item Information -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Food Item Information</h3>
        <div class="space-y-3">
            <div>
                <label class="text-sm font-medium text-gray-500">Title</label>
                <p class="text-gray-900">{{ $foodItem->title }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Description</label>
                <p class="text-gray-900">{{ $foodItem->description }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Category</label>
                <p class="text-gray-900">{{ ucfirst($foodItem->category) }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Price</label>
                <p class="text-gray-900">£{{ $foodItem->price }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Available Quantity</label>
                <p class="text-gray-900">{{ $foodItem->available_quantity }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Pickup Address</label>
                <p class="text-gray-900">{{ $foodItem->pickup_address }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Order Type</label>
                <p class="text-gray-900">{{ ucfirst($foodItem->order_type) }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Available Date</label>
                <p class="text-gray-900">{{ $foodItem->available_date ? $foodItem->available_date->format('M d, Y') : 'N/A' }} {{ $foodItem->available_time ? $foodItem->available_time : '' }}</p>
            </div>
            @if($foodItem->expiry_date)
            <div>
                <label class="text-sm font-medium text-gray-500">Expiry Date</label>
                <p class="text-gray-900">{{ $foodItem->expiry_date->format('M d, Y') }}</p>
            </div>
            @endif
            @if($foodItem->photos && is_array($foodItem->photos) && count($foodItem->photos) > 0)
            <div>
                <label class="text-sm font-medium text-gray-500">Photo</label>
                <img src="{{ is_string($foodItem->photos[0]) ? (Str::startsWith($foodItem->photos[0], ['http://', 'https://']) ? $foodItem->photos[0] : asset('storage/' . ltrim($foodItem->photos[0], '/'))) : '' }}" alt="{{ $foodItem->title }}" class="w-40 h-40 object-cover rounded-lg border mt-2">
            </div>
            @endif
        </div>
    </div>
    <!-- Item Status Card -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Item Status</h3>
        <div class="space-y-3">
            <div>
                <label class="text-sm font-medium text-gray-500">Status</label>
                <div class="flex gap-2 mt-2">
                    @foreach(['active','inactive','expired'] as $status)
                        <form method="POST" action="{{ route('provider.food-items.update', $foodItem) }}" class="inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="{{ $status }}">
                            <button type="submit" class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs focus:outline-none transition {{
                                $foodItem->status === $status ? (
                                    $status === 'active' ? 'bg-green-500 text-white' :
                                    ($status === 'inactive' ? 'bg-gray-500 text-white' : 'bg-red-500 text-white')
                                ) : (
                                    $status === 'active' ? 'bg-green-100 text-green-700 hover:bg-green-200' :
                                    ($status === 'inactive' ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-red-100 text-red-700 hover:bg-red-200')
                                )
                            }}" title="Set status to {{ ucfirst($status) }}">
                                {{ strtoupper(substr($status,0,1)) }}
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Expiry</label>
                <p class="text-gray-900">@if($foodItem->expiry_date){{ $foodItem->expiry_date->format('M d, Y') }}@else N/A @endif</p>
            </div>
        </div>
    </div>
    <!-- Quick Actions Card -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="space-y-3">
            <a href="{{ route('provider.food-items.edit', $foodItem) }}" class="w-full block bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-md font-semibold text-center">Edit</a>
            <a href="{{ route('provider.food-items.create', ['clone_id' => $foodItem->id]) }}" class="w-full block bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md font-semibold text-center">Clone</a>
            <form action="{{ route('provider.food-items.extendExpiry', $foodItem) }}" method="POST" class="w-full flex items-center">
                @csrf
                <input type="number" name="days" min="1" max="365" value="7" class="w-14 text-xs border rounded px-1 mr-1" style="height: 2em;" />
                <button type="submit" class="flex-1 bg-yellow-100 text-yellow-700 hover:bg-yellow-200 text-xs font-semibold py-2 px-3 rounded-lg">Extend</button>
            </form>
            @if($foodItem->status === 'active')
            <form action="{{ route('provider.food-items.markSoldOut', $foodItem) }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="w-full bg-red-100 text-red-700 hover:bg-red-200 text-xs font-semibold py-2 px-3 rounded-lg">Mark Sold Out</button>
            </form>
            @endif
            @if($foodItem->status === 'expired' || $foodItem->status === 'inactive')
            <form action="{{ route('provider.food-items.reactivate', $foodItem) }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="w-full bg-blue-100 text-blue-700 hover:bg-blue-200 text-xs font-semibold py-2 px-3 rounded-lg">Reactivate</button>
            </form>
            @endif
            <form action="{{ route('provider.food-items.destroy', $foodItem) }}" method="POST" class="w-full">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-red-500 text-white hover:bg-red-600 text-xs font-semibold py-2 px-3 rounded-lg" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
            </form>
        </div>
    </div>
</div>
<!-- Add Notes Card -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Add Notes</h3>
        <form method="POST" action="#">
            <textarea class="w-full border rounded-lg p-2 mb-2" rows="3" placeholder="Add notes about this item..."></textarea>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Save Note</button>
        </form>
    </div>
    <!-- Trace/History Card (placeholder) -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Trace / History</h3>
        <ul class="text-sm text-gray-700 dark:text-gray-200 space-y-2">
            <li>Created: {{ $foodItem->created_at->format('M d, Y H:i') }}</li>
            <li>Last Updated: {{ $foodItem->updated_at->format('M d, Y H:i') }}</li>
            <!-- Add more trace/history events as needed -->
        </ul>
    </div>
</div>
<!-- Recent Orders for this Item -->
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-8">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Orders for this Item</h3>
    @if(isset($foodItem->orders) && $foodItem->orders->count() > 0)
    <div class="space-y-3">
        @foreach($foodItem->orders->take(5) as $order)
        <div class="border-b border-gray-200 pb-3">
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-medium text-gray-900">Order #{{ $order->id }}</p>
                    <p class="text-sm text-gray-500">£{{ number_format($order->total_amount, 2) }} • {{ $order->quantity }} items</p>
                </div>
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                    @if($order->status === 'completed') bg-green-100 text-green-800
                    @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                    @else bg-blue-100 text-blue-800
                    @endif">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            <p class="text-xs text-gray-500 mt-1">{{ $order->created_at->format('M d, Y H:i') }}</p>
        </div>
        @endforeach
    </div>
    @else
    <p class="text-gray-500">No orders found for this item.</p>
    @endif
</div>
@endsection 