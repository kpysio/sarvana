<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $foodItem->title }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('food-items.edit', $foodItem) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Edit
                </a>
                <a href="{{ route('food-items.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Photos -->
                        <div>
                            @if($foodItem->photos && is_array($foodItem->photos) && count($foodItem->photos) > 0)
                                <div class="space-y-4">
                                    @foreach($foodItem->photos as $photo)
                                        <img src="{{ $photo }}" alt="{{ $foodItem->title }}" 
                                             class="w-full h-64 object-cover rounded-lg">
                                    @endforeach
                                </div>
                            @else
                                <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-gray-400">No Photos Available</span>
                                </div>
                            @endif
                        </div>

                        <!-- Details -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $foodItem->title }}</h3>
                                <p class="text-gray-600 mt-2">{{ $foodItem->description }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Category</span>
                                    <p class="text-gray-900">{{ ucfirst($foodItem->category) }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Price</span>
                                    <p class="text-2xl font-bold text-green-600">₹{{ $foodItem->price }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Available Quantity</span>
                                    <p class="text-gray-900">{{ $foodItem->available_quantity }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Status</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $foodItem->status === 'active' ? 'bg-green-100 text-green-800' : 
                                           ($foodItem->status === 'inactive' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($foodItem->status) }}
                                    </span>
                                </div>
                            </div>

                            <div>
                                <span class="text-sm font-medium text-gray-500">Available Date</span>
                                <p class="text-gray-900">{{ $foodItem->available_date->format('M d, Y') }}</p>
                            </div>

                            <div>
                                <span class="text-sm font-medium text-gray-500">Available Time</span>
                                <p class="text-gray-900">{{ \Carbon\Carbon::parse($foodItem->available_time)->format('g:i A') }}</p>
                            </div>

                            <div>
                                <span class="text-sm font-medium text-gray-500">Pickup Address</span>
                                <p class="text-gray-900">{{ $foodItem->pickup_address }}</p>
                            </div>

                            <div class="pt-4 border-t border-gray-200">
                                <h4 class="text-lg font-medium text-gray-900 mb-2">Actions</h4>
                                <div class="flex space-x-2">
                                    <a href="{{ route('food-items.edit', $foodItem) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                        Edit Item
                                    </a>
                                    <form action="{{ route('food-items.destroy', $foodItem) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg"
                                                onclick="return confirm('Are you sure you want to delete this item?')">
                                            Delete Item
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 