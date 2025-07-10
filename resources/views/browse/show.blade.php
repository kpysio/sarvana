<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $foodItem->title }}
        </h2>
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
                                <span class="text-sm font-medium text-gray-500">Provider</span>
                                <p class="text-gray-900">{{ $foodItem->provider->name }}</p>
                                <p class="text-sm text-gray-600">Rating: {{ $foodItem->provider->rating }}/5 ({{ $foodItem->provider->total_reviews }} reviews)</p>
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

                            @if($foodItem->status === 'active' && $foodItem->available_quantity > 0)
                                <div class="pt-4 border-t border-gray-200">
                                    <h4 class="text-lg font-medium text-gray-900 mb-2">Order This Item</h4>
                                    <form method="POST" action="{{ route('orders.store') }}" class="space-y-4">
                                        @csrf
                                        <input type="hidden" name="food_item_id" value="{{ $foodItem->id }}">
                                        <input type="hidden" name="provider_id" value="{{ $foodItem->provider_id }}">
                                        
                                        <div>
                                            <x-input-label for="quantity" :value="__('Quantity')" />
                                            <x-text-input id="quantity" class="block mt-1 w-full" type="number" name="quantity" min="1" max="{{ $foodItem->available_quantity }}" value="1" required />
                                            <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="pickup_time" :value="__('Pickup Time')" />
                                            <x-text-input id="pickup_time" class="block mt-1 w-full" type="datetime-local" name="pickup_time" required />
                                            <x-input-error :messages="$errors->get('pickup_time')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="customer_notes" :value="__('Special Instructions (Optional)')" />
                                            <textarea id="customer_notes" name="customer_notes" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                                            <x-input-error :messages="$errors->get('customer_notes')" class="mt-2" />
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <div>
                                                <span class="text-sm font-medium text-gray-500">Total Amount:</span>
                                                <p class="text-xl font-bold text-green-600" id="total-amount">₹{{ $foodItem->price }}</p>
                                            </div>
                                            <x-primary-button>
                                                {{ __('Place Order') }}
                                            </x-primary-button>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <div class="pt-4 border-t border-gray-200">
                                    <p class="text-gray-500">This item is currently not available for ordering.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Related Items -->
                    @if($relatedItems->count() > 0)
                        <div class="mt-12">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Related Items</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                @foreach($relatedItems as $item)
                                    <div class="border rounded-lg p-4">
                                        @if($item->photos && is_array($item->photos) && count($item->photos) > 0)
                                            <img src="{{ $item->photos[0] }}" alt="{{ $item->title }}" 
                                                 class="w-full h-32 object-cover rounded-lg mb-2">
                                        @else
                                            <div class="w-full h-32 bg-gray-200 rounded-lg mb-2 flex items-center justify-center">
                                                <span class="text-gray-400 text-xs">No Image</span>
                                            </div>
                                        @endif
                                        <h4 class="font-medium text-gray-900">{{ $item->title }}</h4>
                                        <p class="text-sm text-gray-600">by {{ $item->provider->name }}</p>
                                        <p class="text-lg font-bold text-green-600">₹{{ $item->price }}</p>
                                        <a href="{{ route('browse.show', $item) }}" class="text-blue-600 hover:text-blue-800 text-sm">View Details</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Calculate total amount based on quantity
        document.getElementById('quantity').addEventListener('change', function() {
            const quantity = parseInt(this.value);
            const price = {{ $foodItem->price }};
            const total = quantity * price;
            document.getElementById('total-amount').textContent = '₹' + total.toFixed(2);
        });
    </script>
</x-app-layout> 