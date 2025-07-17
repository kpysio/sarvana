@extends('layouts.app')

<!-- {{-- Navigation --}}
@auth
    @include('layouts.navigation-customer')
@else
    @include('layouts.navigation-guest')
@endauth -->

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ url()->previous() }}" class="text-orange-600 hover:underline mb-6 inline-block font-semibold">&larr; Back</a>
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <!-- Food Image -->
            @php
                $photos = $foodItem->photos;
                if (is_string($photos)) {
                    $photos = json_decode($photos, true);
                }
                if (!is_array($photos)) {
                    $photos = [];
                }
            @endphp
            @if(count($photos) > 0)
                @php
                    $photo = $photos[0];
                    $isUrl = Str::startsWith($photo, ['http://', 'https://']);
                    $src = $isUrl ? $photo : asset('storage/' . ltrim($photo, '/'));
                @endphp
                <img src="{{ $src }}" alt="{{ $foodItem->title ?? 'Photo' }}" class="w-full h-64 object-cover">
            @else
                <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-400">No Photo</span>
                </div>
            @endif
            <div class="p-6">
                <!-- Badges and Tags -->
                <div class="flex flex-wrap gap-2 mb-2">
                    @if($foodItem->price < 10)
                        <span class="badge bg-green-500 text-white px-2 py-1 rounded text-xs font-semibold">Great Value</span>
                    @endif
                    @if($foodItem->provider && $foodItem->provider->rating >= 4.7)
                        <span class="badge bg-yellow-400 text-gray-900 px-2 py-1 rounded text-xs font-semibold">Top Rated</span>
                    @endif
                    @foreach($foodItem->tags as $tag)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium" style="background: {{ $tag->color ?? '#f59e42' }}20; color: {{ $tag->color ?? '#f59e42' }};">
                            {{ $tag->icon ?? '' }} {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
                <!-- Title & Description -->
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $foodItem->title }}</h1>
                <p class="text-gray-600 text-base mb-4">{{ $foodItem->description }}</p>
                <!-- Provider, Category, Price, Availability -->
                <div class="flex flex-wrap gap-4 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="text-gray-500">By</span>
                        <span class="font-semibold text-gray-800">{{ $foodItem->provider->name ?? '-' }}</span>
                        <span class="text-yellow-400">⭐</span>
                        <span class="text-sm text-gray-600">{{ $foodItem->provider ? number_format($foodItem->provider->rating, 1) : '-' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-500">Category:</span>
                        <span class="font-semibold text-gray-700">{{ $foodItem->category ?? '-' }}</span>
                    </div>
                </div>
                <div class="flex flex-wrap gap-8 mb-6">
                    <div>
                        <div class="text-sm text-gray-500">Price</div>
                        <div class="text-lg font-bold text-orange-600">₹{{ number_format($foodItem->price, 2) }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Available</div>
                        <div class="text-base text-green-600 font-semibold">{{ $foodItem->available_quantity }} left</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Available Date</div>
                        <div class="text-base text-gray-700">{{ $foodItem->available_date ? \Carbon\Carbon::parse($foodItem->available_date)->format('M d, Y') : '-' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Pickup Address</div>
                        <div class="text-base text-gray-700">{{ $foodItem->pickup_address ?? '-' }}</div>
                    </div>
                </div>
                <!-- Order Section -->
                <div class="bg-orange-50 rounded-lg p-6 mb-6">
                    <h2 class="text-lg font-semibold mb-4 text-orange-700">Order This Item</h2>
                    @auth
                        @if(auth()->user()->isCustomer())
                            <form action="{{ url('/customer/orders') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="food_item_id" value="{{ $foodItem->id }}">
                                <input type="hidden" name="provider_id" value="{{ $foodItem->provider_id }}">
                                <div>
                                    <label for="quantity" class="block font-semibold mb-1 text-gray-700">Quantity</label>
                                    <input type="number" name="quantity" id="quantity" min="1" max="{{ $foodItem->available_quantity }}" value="1" class="border border-gray-300 rounded px-3 py-2 w-24 focus:ring-2 focus:ring-orange-500">
                                </div>
                                <div>
                                    <label for="customer_notes" class="block font-semibold mb-1 text-gray-700">Special Instructions</label>
                                    <textarea name="customer_notes" id="customer_notes" rows="2" class="border border-gray-300 rounded px-3 py-2 w-full focus:ring-2 focus:ring-orange-500"></textarea>
                                </div>
                                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-md font-semibold transition">Place Order</button>
                            </form>
                        @else
                            <div class="text-gray-600">Only customers can place orders.</div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-md font-semibold transition inline-block">Login to order</a>
                    @endauth
                </div>
                <!-- Reviews -->
                <div class="bg-white rounded-lg shadow-sm border p-6 mt-6">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800">Reviews</h2>
                    @if($foodItem->reviews->count())
                        <ul>
                            @foreach($foodItem->reviews as $review)
                                <li class="mb-4 border-b pb-4">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-semibold text-gray-700">{{ $review->reviewer->name ?? 'User' }}</span>
                                        <span class="text-yellow-400">⭐</span>
                                        <span class="text-sm text-gray-600">{{ $review->rating }}/5</span>
                                        <span class="text-gray-400 ml-2 text-xs">{{ $review->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <div class="text-gray-700">{{ $review->content }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-gray-500">No reviews yet.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .badge { font-size: 0.8rem; font-weight: 500; padding: 0.25rem 0.5rem; border-radius: 4px; }
</style>
@endsection 