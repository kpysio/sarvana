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

    @if(auth()->user() && auth()->user()->isCustomer())
        <div class="max-w-xl mx-auto mt-10 bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Order This Item</h3>
            <form action="{{ route('orders.store') }}" method="POST">
                @csrf
                <input type="hidden" name="food_item_id" value="{{ $foodItem->id }}">
                <input type="hidden" name="provider_id" value="{{ $foodItem->provider_id }}">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Order Type</label>
                    <select name="order_type" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                        <option value="daily">Daily (one-time)</option>
                        <option value="subscription">Subscription</option>
                        <option value="custom">Custom</option>
                    </select>
                </div>
                <div class="mb-4" id="subscriptionDaysField" style="display:none;">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subscription Days</label>
                    <input type="number" name="subscription_days" min="1" max="30" class="w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div class="mb-4" id="customDetailsField" style="display:none;">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Custom Order Details</label>
                    <textarea name="custom_details" rows="2" class="w-full border border-gray-300 rounded-md px-3 py-2"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                    <input type="number" name="quantity" min="1" max="{{ $foodItem->available_quantity }}" value="1" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                    <span class="text-xs text-gray-500">Available: {{ $foodItem->available_quantity }}</span>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pickup Time</label>
                    <input type="datetime-local" name="pickup_time" class="w-full border border-gray-300 rounded-md px-3 py-2" required value="{{ $foodItem->available_date->format('Y-m-d') }}T{{ $foodItem->available_time }}">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Special Instructions</label>
                    <textarea name="customer_notes" rows="2" class="w-full border border-gray-300 rounded-md px-3 py-2"></textarea>
                </div>
                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded-md">Place Order</button>
            </form>
        </div>
        <script>
            // Show/hide subscription/custom fields
            document.addEventListener('DOMContentLoaded', function() {
                const orderType = document.querySelector('select[name=order_type]');
                const subField = document.getElementById('subscriptionDaysField');
                const customField = document.getElementById('customDetailsField');
                orderType.addEventListener('change', function() {
                    subField.style.display = this.value === 'subscription' ? '' : 'none';
                    customField.style.display = this.value === 'custom' ? '' : 'none';
                });
            });
        </script>
    @endif
</x-app-layout> 