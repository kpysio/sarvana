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
        @include('layouts.navigation-customer')

        <!-- Page Content -->
        <main class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <a href="{{ route('customers.orders.index') }}" class="text-orange-600 dark:text-orange-400 hover:underline mb-6 inline-block font-semibold">&larr; Back to Orders</a>
                @if(!$order->foodItem)
                    <div class="text-red-600 font-bold p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-700">No food item found for this order. Please check the order data.</div>
                @endif
                <!-- Photo Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
                    <div class="p-6 flex justify-center">
                        @php
                            $photos = $order->foodItem->photos ?? [];
                            if (is_string($photos)) {
                                $photos = json_decode($photos, true);
                            }
                            if (!is_array($photos)) {
                                $photos = [];
                            }
                        @endphp
                        <div class="w-full md:w-96 h-64 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden flex items-center justify-center">
                            @if(count($photos) > 0)
                                @php
                                    $photo = $photos[0];
                                    $isUrl = Str::startsWith($photo, ['http://', 'https://']);
                                    $src = $isUrl ? $photo : asset('storage/' . ltrim($photo, '/'));
                                @endphp
                                <img src="{{ $src }}" alt="{{ $order->foodItem->title ?? 'Photo' }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-gray-400 dark:text-gray-500">No Photo</span>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Item Details Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3z"/></svg>
                        Item Details
                    </h2>
                    <!-- Badges and Tags -->
                    <div class="flex flex-wrap gap-2 mb-2">
                        @if($order->foodItem->price < 10)
                            <span class="badge bg-green-500 text-white px-2 py-1 rounded text-xs font-semibold">Great Value</span>
                        @endif
                        @if($order->foodItem->provider && $order->foodItem->provider->rating >= 4.7)
                            <span class="badge bg-yellow-400 text-gray-900 px-2 py-1 rounded text-xs font-semibold">Top Rated</span>
                        @endif
                        @foreach($order->foodItem->tags ?? [] as $tag)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium" style="background: {{ $tag->color ?? '#f59e42' }}20; color: {{ $tag->color ?? '#f59e42' }};">
                                {{ $tag->icon ?? '' }} {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                    <!-- Title & Description -->
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $order->foodItem->title }}</h1>
                    <p class="text-gray-600 dark:text-gray-300 text-base mb-4">{{ $order->foodItem->description }}</p>
                    <!-- Provider, Category, Price, Availability -->
                    <div class="flex flex-wrap gap-4 mb-4">
                        <div class="flex items-center gap-2">
                            <span class="text-gray-500 dark:text-gray-400">By</span>
                            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $order->foodItem->provider->name ?? '-' }}</span>
                            <span class="text-yellow-400">⭐</span>
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $order->foodItem->provider ? number_format($order->foodItem->provider->rating, 1) : '-' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-gray-500 dark:text-gray-400">Category:</span>
                            <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $order->foodItem->category ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-8 mb-6">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Price</div>
                            <div class="text-lg font-bold text-orange-600 dark:text-orange-400">₹{{ number_format($order->foodItem->price, 2) }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Available</div>
                            <div class="text-base text-green-600 dark:text-green-400 font-semibold">{{ $order->foodItem->available_quantity }} left</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Available Date</div>
                            <div class="text-base text-gray-700 dark:text-gray-300">{{ $order->foodItem->available_date ? \Carbon\Carbon::parse($order->foodItem->available_date)->format('M d, Y') : '-' }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Pickup Address</div>
                            <div class="text-base text-gray-700 dark:text-gray-300">{{ $order->foodItem->pickup_address ?? '-' }}</div>
                        </div>
                    </div>
                    @if($order->foodItem->expiry_date)
                    <div class="mb-2"><span class="font-semibold text-gray-800 dark:text-white">Expiry Date:</span> <span class="text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($order->foodItem->expiry_date)->format('M d, Y') }}</span></div>
                    @endif
                </div>
                <!-- Order-specific info card below -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3z"/></svg>
                        Order Details
                    </h2>
                    <div class="flex flex-wrap gap-4 mb-2 text-gray-700 dark:text-gray-300">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3z"/></svg>
                            <span>Quantity: <span class="font-semibold">{{ $order->quantity }}</span></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3z"/></svg>
                            <span>Order Type: <span class="font-semibold">{{ ucfirst($order->order_type) }}</span></span>
                        </div>
                        @if($order->order_type === 'subscription')
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Subscription Days: <span class="font-semibold">{{ $order->subscription_days }}</span></span>
                        </div>
                        @endif
                        @if($order->order_type === 'custom' && $order->custom_details)
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9"/></svg>
                            <span>Custom Details: <span class="font-semibold">{{ $order->custom_details }}</span></span>
                        </div>
                        @endif
                    </div>
                    <div class="flex flex-wrap gap-4 mb-2 text-gray-700 dark:text-gray-300">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Pickup: <span class="font-semibold">{{ $order->pickup_time ? \Carbon\Carbon::parse($order->pickup_time)->format('M d, Y g:i A') : 'Not set' }}</span></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3z"/></svg>
                            <span>Total Amount: <span class="font-semibold">₹{{ number_format($order->total_amount, 2) }}</span></span>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-4 mb-2 text-gray-700 dark:text-gray-300">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6"/></svg>
                            <span>Special Instructions: <span class="font-semibold">{{ $order->customer_notes ?? '-' }}</span></span>
                        </div>
                        @if($order->notes)
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/></svg>
                            <span>Provider/Admin Notes: <span class="font-semibold">{{ $order->notes }}</span></span>
                        </div>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 mt-4">
                        <span class="text-sm font-semibold text-gray-800 dark:text-white">Status:</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                            @if($order->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @elseif($order->status === 'ready') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                            @else bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                            @endif">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M12 6v6l4 2"/>
                            </svg>
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="text-xs text-gray-400 dark:text-gray-500 mt-2">Created: {{ $order->created_at->format('M d, Y g:i A') }} | Updated: {{ $order->updated_at->format('M d, Y g:i A') }}</div>
                </div>
                <!-- Customer & Provider Info Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8 flex flex-col md:flex-row gap-8">
                    <div class="flex-1">
                        <h2 class="text-lg font-semibold mb-2 text-gray-800 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Customer
                        </h2>
                        <div class="mb-1 text-gray-700 dark:text-gray-300"><span class="font-semibold">Name:</span> {{ $order->customer->name ?? '-' }}</div>
                        <div class="mb-1 text-gray-700 dark:text-gray-300"><span class="font-semibold">Email:</span> {{ $order->customer->email ?? '-' }}</div>
                        <div class="mb-1 text-gray-700 dark:text-gray-300"><span class="font-semibold">Phone:</span> {{ $order->customer->phone ?? '-' }}</div>
                        <div class="mb-1 text-gray-700 dark:text-gray-300"><span class="font-semibold">Address:</span> {{ $order->customer->address ?? '-' }}</div>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-lg font-semibold mb-2 text-gray-800 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 17.75l-6.172 3.247 1.179-6.873L2 9.753l6.908-1.004L12 2.5l3.092 6.249L22 9.753l-5.007 4.371 1.179 6.873z"/></svg>
                            Provider
                        </h2>
                        <div class="mb-1 text-gray-700 dark:text-gray-300"><span class="font-semibold">Name:</span> {{ $order->foodItem->provider->name ?? '-' }}</div>
                        <div class="mb-1 text-gray-700 dark:text-gray-300"><span class="font-semibold">Email:</span> {{ $order->foodItem->provider->email ?? '-' }}</div>
                        <div class="mb-1 text-gray-700 dark:text-gray-300"><span class="font-semibold">Phone:</span> {{ $order->foodItem->provider->phone ?? '-' }}</div>
                        <div class="mb-1 text-gray-700 dark:text-gray-300"><span class="font-semibold">Address:</span> {{ $order->foodItem->provider->address ?? '-' }}</div>
                        <div class="mb-1 text-gray-700 dark:text-gray-300"><span class="font-semibold">Rating:</span> {{ $order->foodItem->provider->rating ? number_format($order->foodItem->provider->rating, 1) : '-' }}</div>
                    </div>
                </div>
                <!-- Proof Photo Card -->
                @if($order->proof_photo)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Proof of Collection/Completion
                    </h2>
                    <img src="{{ Str::startsWith($order->proof_photo, ['http://', 'https://']) ? $order->proof_photo : asset('storage/' . ltrim($order->proof_photo, '/')) }}" alt="Proof Photo" class="w-full max-w-xs rounded shadow">
                </div>
                @endif
                <!-- Timeline / Notes -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3z"/></svg>
                        Order Timeline
                    </h2>
                    <ul class="timeline">
                        @php $history = is_array($order->history) ? $order->history : json_decode($order->history, true) ?? []; @endphp
                        <li class="mb-4 flex items-center gap-3">
                            <span class="inline-block w-3 h-3 rounded-full bg-orange-500"></span>
                            <span class="font-bold text-gray-800 dark:text-white">Order Placed</span> by Customer
                            <span class="text-gray-500 dark:text-gray-400 ml-2 text-xs">{{ $order->created_at->format('M d, Y g:i A') }}</span>
                        </li>
                        @foreach($history as $event)
                            @if(is_array($event))
                                <li class="mb-4 flex items-center gap-3">
                                    <span class="inline-block w-3 h-3 rounded-full @if(isset($event['status']) && $event['status'] === 'completed') bg-green-500 @elseif(isset($event['status']) && $event['status'] === 'cancelled') bg-red-500 @elseif(isset($event['status']) && $event['status'] === 'ready') bg-blue-500 @else bg-orange-400 @endif"></span>
                                    <span class="font-bold text-gray-800 dark:text-white">{{ ucfirst($event['action'] ?? '') }}</span>
                                    <span class="text-gray-500 dark:text-gray-400 text-xs">by {{ ucfirst($event['actor'] ?? '') }}</span>
                                    @if(isset($event['status'])) <span class="ml-2 text-xs font-semibold @if($event['status'] === 'completed') text-green-700 dark:text-green-300 @elseif($event['status'] === 'cancelled') text-red-700 dark:text-red-300 @elseif($event['status'] === 'ready') text-blue-700 dark:text-blue-300 @else text-orange-700 dark:text-orange-300 @endif">Status: {{ ucfirst($event['status']) }}</span>@endif
                                    @if(isset($event['note'])) <span class="ml-2 text-xs italic text-purple-700 dark:text-purple-300">Note: {{ $event['note'] }}</span>@endif
                                    <span class="text-gray-400 dark:text-gray-500 ml-2 text-xs">{{ isset($event['timestamp']) ? \Carbon\Carbon::parse($event['timestamp'])->format('M d, Y g:i A') : '' }}</span>
                                </li>
                            @else
                                <li class="mb-4 flex items-center gap-3">
                                    <span class="inline-block w-3 h-3 rounded-full bg-orange-400"></span>
                                    <span class="text-gray-800 dark:text-white">{{ $event }}</span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </main>
        <style>
        .badge { font-size: 0.8rem; font-weight: 500; padding: 0.25rem 0.5rem; border-radius: 4px; }
        .timeline { list-style: none; padding-left: 0; }
        .timeline li { position: relative; margin-bottom: 1em; }
        </style>
        <script type="application/json" id="order-json">{!! $orderJson !!}</script>
    </body>
</html> 