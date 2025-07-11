@extends('layouts.admin')

@section('title', 'Order Details - #' . $order->id)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Order Details</h2>
            <p class="text-gray-600">Order #{{ $order->id }} - {{ $order->created_at->format('M d, Y H:i') }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                Back to Orders
            </a>
        </div>
    </div>
</div>

<!-- Order Information -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Order Details -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Information</h3>
        <div class="space-y-3">
            <div>
                <label class="text-sm font-medium text-gray-500">Order ID</label>
                <p class="text-gray-900 font-medium">#{{ $order->id }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Status</label>
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                    @if($order->status === 'completed') bg-green-100 text-green-800
                    @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                    @else bg-blue-100 text-blue-800
                    @endif">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Quantity</label>
                <p class="text-gray-900">{{ $order->quantity }} items</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Total Amount</label>
                <p class="text-gray-900 font-medium">₹{{ number_format($order->total_amount, 2) }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Pickup Time</label>
                <p class="text-gray-900">{{ $order->pickup_time ? $order->pickup_time->format('M d, Y H:i') : 'Not specified' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Created At</label>
                <p class="text-gray-900">{{ $order->created_at->format('M d, Y H:i') }}</p>
            </div>
            @if($order->notes)
            <div>
                <label class="text-sm font-medium text-gray-500">Notes</label>
                <p class="text-gray-900">{{ $order->notes }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Customer Information -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Information</h3>
        <div class="space-y-3">
            <div>
                <label class="text-sm font-medium text-gray-500">Name</label>
                <p class="text-gray-900">{{ $order->customer->name }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Email</label>
                <p class="text-gray-900">{{ $order->customer->email }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Phone</label>
                <p class="text-gray-900">{{ $order->customer->phone ?? 'Not provided' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Address</label>
                <p class="text-gray-900">{{ $order->customer->address ?? 'Not provided' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Member Since</label>
                <p class="text-gray-900">{{ $order->customer->created_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Provider Information -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Provider Information</h3>
        <div class="space-y-3">
            <div>
                <label class="text-sm font-medium text-gray-500">Name</label>
                <p class="text-gray-900">{{ $order->provider->name }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Email</label>
                <p class="text-gray-900">{{ $order->provider->email }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Phone</label>
                <p class="text-gray-900">{{ $order->provider->phone ?? 'Not provided' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Rating</label>
                <p class="text-gray-900">{{ number_format($order->provider->rating, 1) }}/5.0</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Status</label>
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                    @if($order->provider->membership_status === 'active') bg-green-100 text-green-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst($order->provider->membership_status) }}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Food Item Details -->
<div class="bg-white p-6 rounded-lg shadow mb-8">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Food Item Details</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="text-sm font-medium text-gray-500">Title</label>
            <p class="text-gray-900 font-medium">{{ $order->foodItem->title }}</p>
        </div>
        <div>
            <label class="text-sm font-medium text-gray-500">Price</label>
            <p class="text-gray-900">₹{{ number_format($order->foodItem->price, 2) }}</p>
        </div>
        <div>
            <label class="text-sm font-medium text-gray-500">Category</label>
            <p class="text-gray-900">{{ $order->foodItem->category }}</p>
        </div>
        <div>
            <label class="text-sm font-medium text-gray-500">Status</label>
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                @if($order->foodItem->status === 'active') bg-green-100 text-green-800
                @elseif($order->foodItem->status === 'inactive') bg-gray-100 text-gray-800
                @else bg-red-100 text-red-800
                @endif">
                {{ ucfirst($order->foodItem->status) }}
            </span>
        </div>
        <div class="md:col-span-2">
            <label class="text-sm font-medium text-gray-500">Description</label>
            <p class="text-gray-900">{{ $order->foodItem->description ?? 'No description' }}</p>
        </div>
    </div>
</div>

<!-- Status Update -->
<div class="bg-white p-6 rounded-lg shadow">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Update Order Status</h3>
    <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
        @csrf
        @method('PATCH')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">New Status</label>
                <select id="status" name="status" 
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea id="notes" name="notes" rows="3" 
                          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500"
                          placeholder="Add any notes about this status change...">{{ $order->notes }}</textarea>
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <button type="submit" 
                    class="px-6 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700">
                Update Status
            </button>
        </div>
    </form>
</div>

<!-- Reviews (if any) -->
@if($order->reviews->count() > 0)
<div class="mt-8 bg-white p-6 rounded-lg shadow">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Reviews</h3>
    <div class="space-y-4">
        @foreach($order->reviews as $review)
        <div class="border-b border-gray-200 pb-4 last:border-b-0">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-900">{{ $review->rating }}/5</span>
                    <div class="flex space-x-1">
                        @for($i = 1; $i <= 5; $i++)
                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                             fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        @endfor
                    </div>
                </div>
                <span class="text-sm text-gray-500">{{ $review->created_at->format('M d, Y') }}</span>
            </div>
            @if($review->comment)
            <p class="text-gray-700">{{ $review->comment }}</p>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection 