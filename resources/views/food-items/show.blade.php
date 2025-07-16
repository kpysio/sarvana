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
{{-- Order Analysis and Status Breakdown --}}
<div class="mb-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($orderStats['total_orders']) }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-500">Completion Rate</p>
                <p class="text-xl font-semibold text-gray-900">{{ number_format($orderStats['completion_rate'], 1) }}%</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Completed Orders</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($orderStats['completed_orders']) }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-500">Success Rate</p>
                <p class="text-xl font-semibold text-gray-900">{{ $orderStats['total_orders'] ? number_format(($orderStats['completed_orders'] / $orderStats['total_orders']) * 100, 1) : '0.0' }}%</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending Orders</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($orderStats['pending_orders']) }}</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-500">Pending Rate</p>
                <p class="text-xl font-semibold text-gray-900">{{ $orderStats['total_orders'] ? number_format(($orderStats['pending_orders'] / $orderStats['total_orders']) * 100, 1) : '0.0' }}%</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Cancelled Orders</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($orderStats['cancelled_orders']) }}</p>
                </div>
                <div class="p-3 bg-red-100 rounded-full">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-500">Cancellation Rate</p>
                <p class="text-xl font-semibold text-gray-900">{{ $orderStats['total_orders'] ? number_format(($orderStats['cancelled_orders'] / $orderStats['total_orders']) * 100, 1) : '0.0' }}%</p>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Status Breakdown</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @php $totalOrders = $orderStats['total_orders']; @endphp
            @foreach($orderStatusBreakdown as $status => $count)
            <div class="text-center p-4 rounded-lg
                @if($status === 'completed') bg-green-50 border border-green-200
                @elseif($status === 'pending') bg-yellow-50 border border-yellow-200
                @elseif($status === 'cancelled') bg-red-50 border border-red-200
                @else bg-gray-50 border border-gray-200
                @endif">
                <div class="text-2xl font-bold
                    @if($status === 'completed') text-green-600
                    @elseif($status === 'pending') text-yellow-600
                    @elseif($status === 'cancelled') text-red-600
                    @else text-gray-600
                    @endif">
                    {{ number_format($count) }}
                </div>
                <div class="text-sm font-medium text-gray-700 capitalize">{{ $status }}</div>
                <div class="text-xs text-gray-500">{{ $totalOrders ? number_format(($count / $totalOrders) * 100, 1) : '0.0' }}%</div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Inventory Item Quantity Breakdown -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow flex flex-col items-center">
        <div class="p-3 bg-blue-100 rounded-full mb-2">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
            </svg>
        </div>
        <div class="text-sm font-medium text-gray-600">Total Quantity</div>
        <div class="text-2xl font-bold text-gray-900">{{ $inventoryBreakdown['total'] }}</div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow flex flex-col items-center">
        <div class="p-3 bg-yellow-100 rounded-full mb-2">
            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
            </svg>
        </div>
        <div class="text-sm font-medium text-gray-600">Ordered</div>
        <div class="text-2xl font-bold text-gray-900">{{ $inventoryBreakdown['ordered'] }}</div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow flex flex-col items-center">
        <div class="p-3 bg-green-100 rounded-full mb-2">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <div class="text-sm font-medium text-gray-600">Remaining</div>
        <div class="text-2xl font-bold text-gray-900">{{ $inventoryBreakdown['remaining'] }}</div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow flex flex-col items-center">
        <div class="p-3 bg-purple-100 rounded-full mb-2">
            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2" />
            </svg>
        </div>
        <div class="text-sm font-medium text-gray-600">Order Rate</div>
        <div class="text-2xl font-bold text-gray-900">{{ number_format($inventoryBreakdown['order_rate'], 1) }}%</div>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
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
                <label class="text-sm font-medium text-gray-500">Remaining Quantity</label>
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
    <!-- Tags Card -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Tags</h3>
        <div class="flex flex-wrap gap-2">
            @forelse($foodItem->tags as $tag)
                <div class="flex items-center gap-2 px-3 py-2 rounded shadow text-sm font-semibold" style="background: {{ $tag->color ?? '#f3f4f6' }}; color: #222; min-width: 120px;">
                    <span class="text-lg">{{ $tag->icon }}</span>
                    <span>{{ $tag->name }}</span>
                </div>
            @empty
                <span class="text-gray-500">No tags applied.</span>
            @endforelse
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