<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ dark: false }" :class="{ 'dark': dark }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
        <!-- Navigation -->
        @auth
            @include('layouts.navigation-customer')
        @else
            @include('layouts.navigation-guest')
        @endauth

        <!-- Page Content -->
        <main class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
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
                                    <div class="w-full h-64 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                        <span class="text-gray-400 dark:text-gray-500">No Photos Available</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Details -->
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $foodItem->title }}</h3>
                                    <p class="text-gray-600 dark:text-gray-300 mt-2">{{ $foodItem->description }}</p>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Category</span>
                                        <p class="text-gray-900 dark:text-white">{{ ucfirst($foodItem->category) }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Price</span>
                                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">₹{{ $foodItem->price }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Available Quantity</span>
                                        <p class="text-gray-900 dark:text-white">{{ $foodItem->available_quantity }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $foodItem->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                               ($foodItem->status === 'inactive' ? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                                            {{ ucfirst($foodItem->status) }}
                                        </span>
                                    </div>
                                </div>

                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Provider</span>
                                    <p class="text-gray-900 dark:text-white">{{ $foodItem->provider->name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Rating: {{ $foodItem->provider->rating }}/5 ({{ $foodItem->provider->total_reviews }} reviews)</p>
                                </div>

                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Available Date</span>
                                    <p class="text-gray-900 dark:text-white">{{ $foodItem->available_date->format('M d, Y') }}</p>
                                </div>

                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Available Time</span>
                                    <p class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($foodItem->available_time)->format('g:i A') }}</p>
                                </div>

                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Pickup Address</span>
                                    <p class="text-gray-900 dark:text-white">{{ $foodItem->pickup_address }}</p>
                                </div>

                                @if($foodItem->status === 'active' && $foodItem->available_quantity > 0)
                                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Order This Item</h4>
                                        <form method="POST" action="{{ url('/customer/orders') }}" class="space-y-4">
                                            @csrf
                                            <input type="hidden" name="food_item_id" value="{{ $foodItem->id }}">
                                            <input type="hidden" name="provider_id" value="{{ $foodItem->provider_id }}">
                                            
                                            <div>
                                                <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity</label>
                                                <input id="quantity" class="block mt-1 w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" type="number" name="quantity" min="1" max="{{ $foodItem->available_quantity }}" value="1" required />
                                                @error('quantity')
                                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div>
                                                <label for="pickup_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pickup Time</label>
                                                <input id="pickup_time" class="block mt-1 w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" type="datetime-local" name="pickup_time" required />
                                                @error('pickup_time')
                                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div>
                                                <label for="customer_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Special Instructions (Optional)</label>
                                                <textarea id="customer_notes" name="customer_notes" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm dark:bg-gray-700 dark:text-white"></textarea>
                                                @error('customer_notes')
                                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Amount:</span>
                                                    <p class="text-xl font-bold text-green-600 dark:text-green-400" id="total-amount">₹{{ $foodItem->price }}</p>
                                                </div>
                                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    Place Order
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @else
                                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <p class="text-gray-500 dark:text-gray-400">This item is currently not available for ordering.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Related Items -->
                        @if($relatedItems->count() > 0)
                            <div class="mt-12">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Related Items</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    @foreach($relatedItems as $item)
                                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-white dark:bg-gray-800">
                                            @if($item->photos && is_array($item->photos) && count($item->photos) > 0)
                                                <img src="{{ $item->photos[0] }}" alt="{{ $item->title }}" 
                                                     class="w-full h-32 object-cover rounded-lg mb-2">
                                            @else
                                                <div class="w-full h-32 bg-gray-200 dark:bg-gray-700 rounded-lg mb-2 flex items-center justify-center">
                                                    <span class="text-gray-400 dark:text-gray-500 text-xs">No Image</span>
                                                </div>
                                            @endif
                                            <h4 class="font-medium text-gray-900 dark:text-white">{{ $item->title }}</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">by {{ $item->provider->name }}</p>
                                            <p class="text-lg font-bold text-green-600 dark:text-green-400">₹{{ $item->price }}</p>
                                            <a href="{{ route('search.show', $item) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm">View Details</a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>

        <script>
            // Calculate total amount based on quantity
            document.getElementById('quantity').addEventListener('change', function() {
                const quantity = parseInt(this.value);
                const price = {{ $foodItem->price }};
                const total = quantity * price;
                document.getElementById('total-amount').textContent = '₹' + total.toFixed(2);
            });
        </script>
    </body>
</html> 