<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Provider Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Total Revenue</div>
                        <div class="text-2xl font-bold text-green-600">£{{ number_format($totalRevenue, 2) }}</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Pending Orders</div>
                        <div class="text-2xl font-bold text-orange-600">{{ $pendingOrders }}</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Active Items</div>
                        <div class="text-2xl font-bold text-blue-600">{{ $foodItems->count() }}</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Rating</div>
                        <div class="text-2xl font-bold text-yellow-600">{{ auth()->user()->rating }}/5</div>
                    </div>
                </div>
            </div>

            <!-- Recent Food Items -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Recent Food Items</h3>
                </div>
                <div class="p-6">
                    @if($foodItems->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($foodItems as $item)
                                <div class="border rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900">{{ $item->title }}</h4>
                                    <p class="text-sm text-gray-600">{{ $item->category }}</p>
                                    <p class="text-lg font-bold text-green-600">£{{ $item->price }}</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $item->status === 'active' ? 'bg-green-100 text-green-800' : 
                                           ($item->status === 'inactive' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No food items yet. <a href="{{ route('provider.food-items.create') }}" class="text-blue-600 hover:text-blue-800">Create your first item</a></p>
                    @endif
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
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
                                            <p class="text-sm text-gray-600">Ordered by {{ $order->customer->name }}</p>
                                            <p class="text-sm text-gray-600">Quantity: {{ $order->quantity }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-green-600">£{{ $order->total_amount }}</p>
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
                        <p class="text-gray-500">No orders yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 