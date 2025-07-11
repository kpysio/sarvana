@extends('layouts.admin')

@section('title', 'User Details - ' . $user->name)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">User Details</h2>
            <p class="text-gray-600">{{ $user->name }} ({{ $user->email }})</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Edit User
            </a>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                Back to Users
            </a>
        </div>
    </div>
</div>

<!-- User Information -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Basic Info -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h3>
        <div class="space-y-3">
            <div>
                <label class="text-sm font-medium text-gray-500">Name</label>
                <p class="text-gray-900">{{ $user->name }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Email</label>
                <p class="text-gray-900">{{ $user->email }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Phone</label>
                <p class="text-gray-900">{{ $user->phone ?? 'Not provided' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Address</label>
                <p class="text-gray-900">{{ $user->address ?? 'Not provided' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Bio</label>
                <p class="text-gray-900">{{ $user->bio ?? 'No bio provided' }}</p>
            </div>
        </div>
    </div>

    <!-- Account Status -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Account Status</h3>
        <div class="space-y-3">
            <div>
                <label class="text-sm font-medium text-gray-500">User Type</label>
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                    @if($user->user_type === 'admin') bg-purple-100 text-purple-800
                    @elseif($user->user_type === 'provider') bg-orange-100 text-orange-800
                    @else bg-blue-100 text-blue-800
                    @endif">
                    {{ ucfirst($user->user_type) }}
                </span>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Membership Status</label>
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                    @if($user->membership_status === 'active') bg-green-100 text-green-800
                    @elseif($user->membership_status === 'expired') bg-red-100 text-red-800
                    @else bg-yellow-100 text-yellow-800
                    @endif">
                    {{ ucfirst($user->membership_status ?? 'pending') }}
                </span>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Verification</label>
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                    @if($user->is_verified) bg-green-100 text-green-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ $user->is_verified ? 'Verified' : 'Not Verified' }}
                </span>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Rating</label>
                <p class="text-gray-900">{{ number_format($user->rating, 1) }}/5.0 ({{ $user->total_reviews }} reviews)</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Member Since</label>
                <p class="text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
            </div>
            @if($user->membership_expires_at)
            <div>
                <label class="text-sm font-medium text-gray-500">Membership Expires</label>
                <p class="text-gray-900">{{ $user->membership_expires_at->format('M d, Y') }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="space-y-3">
            @if($user->user_type === 'provider')
                @if($user->membership_status !== 'active')
                <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                    @csrf
                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                        Approve Provider
                    </button>
                </form>
                @else
                <form method="POST" action="{{ route('admin.users.reject', $user) }}">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                        Reject Provider
                    </button>
                </form>
                @endif
            @endif

            @if($user->user_type !== 'admin')
                @if($user->membership_status === 'active')
                <form method="POST" action="{{ route('admin.users.deactivate', $user) }}">
                    @csrf
                    <button type="submit" class="w-full bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700">
                        Deactivate Account
                    </button>
                </form>
                @else
                <form method="POST" action="{{ route('admin.users.reactivate', $user) }}">
                    @csrf
                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                        Reactivate Account
                    </button>
                </form>
                @endif

                <form method="POST" action="{{ route('admin.users.reset-password', $user) }}">
                    @csrf
                    <button type="submit" class="w-full bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700">
                        Reset Password
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<!-- User Activity -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Orders -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Orders</h3>
        @if($user->customerOrders->count() > 0)
        <div class="space-y-3">
            @foreach($user->customerOrders->take(5) as $order)
            <div class="border-b border-gray-200 pb-3">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-medium text-gray-900">{{ $order->foodItem->title }}</p>
                        <p class="text-sm text-gray-500">₹{{ number_format($order->total_amount, 2) }} • {{ $order->quantity }} items</p>
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
        <p class="text-gray-500">No orders found.</p>
        @endif
    </div>

    <!-- Provider Orders (if provider) -->
    @if($user->user_type === 'provider')
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Provider Orders</h3>
        @if($user->providerOrders->count() > 0)
        <div class="space-y-3">
            @foreach($user->providerOrders->take(5) as $order)
            <div class="border-b border-gray-200 pb-3">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-medium text-gray-900">{{ $order->foodItem->title }}</p>
                        <p class="text-sm text-gray-500">₹{{ number_format($order->total_amount, 2) }} • {{ $order->quantity }} items</p>
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
        <p class="text-gray-500">No provider orders found.</p>
        @endif
    </div>
    @endif
</div>

<!-- Food Items (if provider) -->
@if($user->user_type === 'provider' && $user->foodItems->count() > 0)
<div class="mt-8 bg-white p-6 rounded-lg shadow">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Food Items</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($user->foodItems->take(6) as $item)
        <div class="border border-gray-200 rounded-lg p-4">
            <h4 class="font-medium text-gray-900">{{ $item->title }}</h4>
            <p class="text-sm text-gray-500">₹{{ number_format($item->price, 2) }}</p>
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                @if($item->status === 'active') bg-green-100 text-green-800
                @elseif($item->status === 'inactive') bg-gray-100 text-gray-800
                @else bg-red-100 text-red-800
                @endif">
                {{ ucfirst($item->status) }}
            </span>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection 