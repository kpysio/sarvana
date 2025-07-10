<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Customer Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Quick Actions -->
            <div class="mb-6">
                <div class="flex space-x-4">
                    <a href="{{ route('browse.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Browse Food Items
                    </a>
                    <a href="{{ route('browse.providers') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                        Find Providers
                    </a>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Recent Orders</h3>
                </div>
                <div class="p-6">
                    @if($recentOrders->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentOrders as $order)
                                <div class="border rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $order->foodItem->title }}</h4>
                                            <p class="text-sm text-gray-600">From {{ $order->provider->name }}</p>
                                            <p class="text-sm text-gray-600">Quantity: {{ $order->quantity }}</p>
                                            <p class="text-sm text-gray-600">Pickup: {{ $order->pickup_time->format('M d, Y g:i A') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-green-600">₹{{ $order->total_amount }}</p>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($order->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : 
                                                   ($order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No orders yet. <a href="{{ route('browse.index') }}" class="text-blue-600 hover:text-blue-800">Start browsing</a></p>
                    @endif
                </div>
            </div>

            <!-- Followed Providers -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Followed Providers</h3>
                </div>
                <div class="p-6">
                    @if($followedProviders->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($followedProviders as $follow)
                                <div class="border rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900">{{ $follow->provider->name }}</h4>
                                    <p class="text-sm text-gray-600">Rating: {{ $follow->provider->rating }}/5</p>
                                    <p class="text-sm text-gray-600">{{ $follow->provider->foodItems->count() }} items</p>
                                    <a href="{{ route('browse.provider', $follow->provider) }}" class="text-blue-600 hover:text-blue-800 text-sm">View Items</a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">Not following any providers yet. <a href="{{ route('browse.providers') }}" class="text-blue-600 hover:text-blue-800">Find providers to follow</a></p>
                    @endif
                </div>
            </div>

            <!-- Recommended Items -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Recommended for You</h3>
                </div>
                <div class="p-6">
                    @if($recommendedItems->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($recommendedItems as $item)
                                <div class="border rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900">{{ $item->title }}</h4>
                                    <p class="text-sm text-gray-600">{{ $item->category }}</p>
                                    <p class="text-sm text-gray-600">by {{ $item->provider->name }}</p>
                                    <p class="text-lg font-bold text-green-600">₹{{ $item->price }}</p>
                                    <a href="{{ route('browse.show', $item) }}" class="text-blue-600 hover:text-blue-800 text-sm">View Details</a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No recommendations available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 