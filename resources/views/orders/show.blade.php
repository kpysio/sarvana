<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Order #{{ $order->id }}
            </h2>
            <a href="{{ route('orders.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Back to Orders
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Order Details -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Order Details</h3>
                            <div class="space-y-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Order ID</span>
                                    <p class="text-gray-900">#{{ $order->id }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Status</span>
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
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Order Date</span>
                                    <p class="text-gray-900">{{ $order->created_at->format('M d, Y g:i A') }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Pickup Time</span>
                                    <p class="text-gray-900">{{ $order->pickup_time->format('M d, Y g:i A') }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Quantity</span>
                                    <p class="text-gray-900">{{ $order->quantity }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Total Amount</span>
                                    <p class="text-2xl font-bold text-green-600">₹{{ $order->total_amount }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Food Item Details -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Food Item</h3>
                            <div class="border rounded-lg p-4">
                                @if($order->foodItem->photos && is_array($order->foodItem->photos) && count($order->foodItem->photos) > 0)
                                    <img src="{{ $order->foodItem->photos[0] }}" alt="{{ $order->foodItem->title }}" 
                                         class="w-full h-32 object-cover rounded-lg mb-4">
                                @else
                                    <div class="w-full h-32 bg-gray-200 rounded-lg mb-4 flex items-center justify-center">
                                        <span class="text-gray-400">No Image</span>
                                    </div>
                                @endif
                                
                                <h4 class="font-medium text-gray-900">{{ $order->foodItem->title }}</h4>
                                <p class="text-sm text-gray-600">{{ $order->foodItem->description }}</p>
                                <p class="text-sm text-gray-600 mt-2">Category: {{ ucfirst($order->foodItem->category) }}</p>
                                <p class="text-sm text-gray-600">Price: ₹{{ $order->foodItem->price }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- User Details -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                        @if(auth()->user()->isProvider())
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Details</h3>
                                <div class="space-y-2">
                                    <p><span class="font-medium">Name:</span> {{ $order->customer->name }}</p>
                                    <p><span class="font-medium">Email:</span> {{ $order->customer->email }}</p>
                                    @if($order->customer->phone)
                                        <p><span class="font-medium">Phone:</span> {{ $order->customer->phone }}</p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Provider Details</h3>
                                <div class="space-y-2">
                                    <p><span class="font-medium">Name:</span> {{ $order->provider->name }}</p>
                                    <p><span class="font-medium">Email:</span> {{ $order->provider->email }}</p>
                                    @if($order->provider->phone)
                                        <p><span class="font-medium">Phone:</span> {{ $order->provider->phone }}</p>
                                    @endif
                                    <p><span class="font-medium">Rating:</span> {{ $order->provider->rating }}/5</p>
                                </div>
                            </div>
                        @endif

                        <!-- Pickup Address -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Pickup Address</h3>
                            <p class="text-gray-900">{{ $order->foodItem->pickup_address }}</p>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($order->customer_notes || $order->notes)
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Notes</h3>
                            @if($order->customer_notes)
                                <div class="mb-4">
                                    <span class="text-sm font-medium text-gray-500">Customer Notes:</span>
                                    <p class="text-gray-900 mt-1">{{ $order->customer_notes }}</p>
                                </div>
                            @endif
                            @if($order->notes)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Provider Notes:</span>
                                    <p class="text-gray-900 mt-1">{{ $order->notes }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Status Timeline/Progress Bar -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Progress</h3>
                        <div class="flex items-center space-x-4">
                            @php
                                $steps = ['pending','accepted','preparing','ready','collected','completed'];
                                $current = array_search($order->status, $steps);
                            @endphp
                            @foreach($steps as $i => $step)
                                <div class="flex items-center">
                                    <span class="w-8 h-8 flex items-center justify-center rounded-full text-xs font-bold
                                        @if($i < $current) bg-green-500 text-white
                                        @elseif($i == $current) bg-blue-500 text-white
                                        @else bg-gray-200 text-gray-500
                                        @endif">
                                        {{ $i+1 }}
                                    </span>
                                    @if($i < count($steps)-1)
                                        <span class="w-8 h-1 bg-gray-300 mx-1"></span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-2">
                            <span>Placed</span><span>Accepted</span><span>Preparing</span><span>Ready</span><span>Collected</span><span>Completed</span>
                        </div>
                    </div>

                    <!-- Proof Photo -->
                    @if($order->proof_photo)
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Proof of Preparation</h3>
                            <img src="{{ asset('storage/' . $order->proof_photo) }}" alt="Proof Photo" class="w-full max-w-xs rounded-lg shadow">
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                        <div class="flex space-x-4">
                            @if(auth()->user()->isProvider())
                                @if($order->status === 'pending')
                                    <form action="{{ route('orders.accept', $order) }}" method="POST" class="inline">@csrf<button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg mr-2">Accept</button></form>
                                    <form action="{{ route('orders.reject', $order) }}" method="POST" class="inline">@csrf<button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg mr-2">Reject</button></form>
                                @elseif($order->status === 'accepted')
                                    <form action="{{ route('orders.preparing', $order) }}" method="POST" class="inline">@csrf<button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg mr-2">Set Preparing</button></form>
                                @elseif($order->status === 'preparing')
                                    <form action="{{ route('orders.ready', $order) }}" method="POST" enctype="multipart/form-data" class="inline">@csrf<input type="file" name="proof_photo" required class="inline-block text-xs mr-2"><button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">Mark Ready</button></form>
                                @elseif($order->status === 'ready')
                                    <form action="{{ route('orders.collected', $order) }}" method="POST" class="inline">@csrf<button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg mr-2">Mark Collected</button></form>
                                @elseif($order->status === 'collected')
                                    <form action="{{ route('orders.completed', $order) }}" method="POST" class="inline">@csrf<button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">Mark Completed</button></form>
                                @endif
                            @endif
                            @if(auth()->user()->isCustomer() && $order->status === 'pending')
                                <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline">@csrf<button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">Cancel Order</button></form>
                            @endif
                            @if(auth()->user()->isCustomer() && $order->status === 'ready' && $order->proof_photo)
                                <a href="{{ asset('storage/' . $order->proof_photo) }}" target="_blank" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">View Proof</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 