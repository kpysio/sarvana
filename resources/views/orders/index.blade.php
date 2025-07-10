<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ auth()->user()->isProvider() ? 'My Orders (Provider)' : 'My Orders (Customer)' }}
        </h2>
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
                    @if($orders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Order ID
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Item
                                        </th>
                                        @if(auth()->user()->isProvider())
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Customer
                                            </th>
                                        @else
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Provider
                                            </th>
                                        @endif
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Quantity
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total Amount
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pickup Time
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($orders as $order)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                #{{ $order->id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if($order->foodItem->photos && is_array($order->foodItem->photos) && count($order->foodItem->photos) > 0)
                                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $order->foodItem->photos[0] }}" alt="">
                                                    @else
                                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                            <span class="text-gray-400 text-xs">No Image</span>
                                                        </div>
                                                    @endif
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $order->foodItem->title }}</div>
                                                        <div class="text-sm text-gray-500">{{ $order->foodItem->category }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            @if(auth()->user()->isProvider())
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $order->customer->name }}
                                                </td>
                                            @else
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $order->provider->name }}
                                                </td>
                                            @endif
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $order->quantity }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                                ₹{{ $order->total_amount }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
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
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $order->pickup_time->format('M d, Y g:i A') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                                @if(auth()->user()->isProvider())
                                                    @if($order->status === 'pending')
                                                        <form action="{{ route('orders.accept', $order) }}" method="POST" class="inline">@csrf<button type="submit" class="text-green-600 hover:text-green-900 mr-2">Accept</button></form>
                                                        <form action="{{ route('orders.reject', $order) }}" method="POST" class="inline">@csrf<button type="submit" class="text-red-600 hover:text-red-900 mr-2">Reject</button></form>
                                                    @elseif($order->status === 'accepted')
                                                        <form action="{{ route('orders.preparing', $order) }}" method="POST" class="inline">@csrf<button type="submit" class="text-orange-600 hover:text-orange-900 mr-2">Set Preparing</button></form>
                                                    @elseif($order->status === 'preparing')
                                                        <form action="{{ route('orders.ready', $order) }}" method="POST" enctype="multipart/form-data" class="inline">@csrf<input type="file" name="proof_photo" required class="inline-block text-xs mr-2"><button type="submit" class="text-green-600 hover:text-green-900 mr-2">Mark Ready</button></form>
                                                    @elseif($order->status === 'ready')
                                                        <form action="{{ route('orders.collected', $order) }}" method="POST" class="inline">@csrf<button type="submit" class="text-indigo-600 hover:text-indigo-900 mr-2">Mark Collected</button></form>
                                                    @elseif($order->status === 'collected')
                                                        <form action="{{ route('orders.completed', $order) }}" method="POST" class="inline">@csrf<button type="submit" class="text-gray-600 hover:text-gray-900 mr-2">Mark Completed</button></form>
                                                    @endif
                                                @endif
                                                @if(auth()->user()->isCustomer() && $order->status === 'pending')
                                                    <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline">@csrf<button type="submit" class="text-red-600 hover:text-red-900">Cancel</button></form>
                                                @endif
                                                @if(auth()->user()->isCustomer() && $order->status === 'ready' && $order->proof_photo)
                                                    <a href="{{ asset('storage/' . $order->proof_photo) }}" target="_blank" class="text-green-600 hover:text-green-900 ml-2">View Proof</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-500 mb-4">No orders found.</p>
                            @if(auth()->user()->isCustomer())
                                <a href="{{ route('browse.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                    Browse Food Items
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 