@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header: Search, Pills, Sort, View Toggle -->
    <div class="bg-white shadow-sm border-b sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Search Bar -->
                <div class="flex-1 max-w-2xl">
                    <form method="GET" action="{{ route('search.index') }}" class="relative">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Search for biryani, thepla, dosa..."
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        >
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <button type="submit" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <span class="bg-orange-500 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-orange-600">
                                Search
                            </span>
                        </button>
                    </form>
                </div>
                <!-- Pills & View Toggle -->
                <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4 mt-4 md:mt-0">
                    <!-- Filter Pills -->
                    <div class="flex flex-wrap gap-2">
                        <button class="filter-btn px-3 py-1 rounded-full border text-sm {{ request('value') ? 'bg-green-500 text-white border-green-500' : 'bg-gray-100 border-gray-300' }}" onclick="window.location='?value=1'">Great Value for Money</button>
                        <button class="filter-btn px-3 py-1 rounded-full border text-sm {{ request('top') ? 'bg-yellow-400 text-gray-900 border-yellow-400' : 'bg-gray-100 border-gray-300' }}" onclick="window.location='?top=1'">Top Rated Deals</button>
                    </div>
                    <!-- View Toggle -->
                    <div class="flex items-center ml-2">
                        <button id="gridBtn" class="view-btn px-2 py-1 rounded {{ !request('view') || request('view')=='grid' ? 'bg-white shadow' : '' }}" onclick="setView('grid')">⊞</button>
                        <button id="listBtn" class="view-btn px-2 py-1 rounded {{ request('view')=='list' ? 'bg-white shadow' : '' }}" onclick="setView('list')">☰</button>
                    </div>
                    <!-- Sort -->
                    <form method="GET" action="{{ route('search.index') }}" class="ml-2">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <select name="sort" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Latest</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
                        </div>
                        
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col lg:flex-row gap-8">
        <!-- Sticky Filters Sidebar (desktop only) -->
        <aside class="hidden lg:block lg:w-1/4 flex-shrink-0 sticky top-24 self-start z-20">
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Filters</h3>
                <form method="GET" action="{{ route('search.index') }}" id="filterForm">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <!-- Location Filter -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">📍 Location</label>
                        <select name="postcode" onchange="document.getElementById('filterForm').submit()" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                            <option value="">All Areas</option>
                            @foreach($postcodes as $postcode)
                                <option value="{{ $postcode }}" {{ request('postcode') == $postcode ? 'selected' : '' }}>
                                    {{ $postcode }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Tag Filters -->
                    @foreach($tags as $category => $categoryTags)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                @switch($category)
                                    @case('food_type') 🏷️ Food Type @break
                                    @case('festival') 🎉 Festival @break
                                    @case('seasonal') 🌤️ Seasonal @break
                                    @case('food_origin') 🌍 Food Origin @break
                                @endswitch
                            </label>
                            <div class="space-y-2">
                                @foreach($categoryTags as $tag)
                                    <label class="flex items-center">
                                        <input 
                                            type="checkbox" 
                                            name="tags[]" 
                                            value="{{ $tag->id }}"
                                            {{ (is_array(request('tags')) && in_array($tag->id, request('tags', []))) || (is_string(request('tags')) && in_array($tag->id, explode(',', request('tags')))) ? 'checked' : '' }}
                                            onchange="document.getElementById('filterForm').submit()"
                                            class="rounded border-gray-300 text-orange-600 focus:ring-orange-500"
                                        >
                                        <span class="ml-2 text-sm text-gray-700">{{ $tag->icon }} {{ $tag->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                    <!-- Price Range -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">💰 Price Range</label>
                        <div class="flex items-center gap-2">
                            <input 
                                type="number" 
                                name="min_price" 
                                value="{{ request('min_price') }}"
                                placeholder="Min"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                            >
                            <span class="text-gray-500">-</span>
                            <input 
                                type="number" 
                                name="max_price" 
                                value="{{ request('max_price') }}"
                                placeholder="Max"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                            >
                        </div>
                    </div>
                    <!-- Rating Filter -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">⭐ Minimum Rating</label>
                        <select name="min_rating" onchange="document.getElementById('filterForm').submit()" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                            <option value="">Any Rating</option>
                            <option value="4" {{ request('min_rating') == '4' ? 'selected' : '' }}>4+ Stars</option>
                            <option value="3" {{ request('min_rating') == '3' ? 'selected' : '' }}>3+ Stars</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-orange-500 text-white py-2 px-4 rounded-md hover:bg-orange-600 transition">
                        Apply Filters
                    </button>
                </form>
            </div>
        </aside>
        <!-- Mobile Filters (collapsible, only on mobile) -->
        <aside class="block lg:hidden w-full mb-4">
            <div class="bg-white rounded-lg shadow-sm border p-4">
                <button type="button" class="w-full flex items-center justify-between px-4 py-2 bg-white border rounded-lg shadow-sm mb-4" onclick="document.getElementById('mobileFiltersPanel').classList.toggle('hidden')">
                    <span class="font-semibold text-gray-900">Filters</span>
                    <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="mobileFiltersPanel" class="hidden">
                    <form method="GET" action="{{ route('search.index') }}" id="mobileFilterForm">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <!-- (Repeat all filter fields as above for mobile) -->
                        <!-- Location Filter -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">📍 Location</label>
                            <select name="postcode" onchange="document.getElementById('mobileFilterForm').submit()" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                                <option value="">All Areas</option>
                                @foreach($postcodes as $postcode)
                                    <option value="{{ $postcode }}" {{ request('postcode') == $postcode ? 'selected' : '' }}>
                                        {{ $postcode }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Tag Filters -->
                        @foreach($tags as $category => $categoryTags)
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    @switch($category)
                                        @case('food_type') 🏷️ Food Type @break
                                        @case('festival') 🎉 Festival @break
                                        @case('seasonal') 🌤️ Seasonal @break
                                        @case('food_origin') 🌍 Food Origin @break
                                    @endswitch
                                </label>
                                <div class="space-y-2">
                                    @foreach($categoryTags as $tag)
                                        <label class="flex items-center">
                                            <input 
                                                type="checkbox" 
                                                name="tags[]" 
                                                value="{{ $tag->id }}"
                                                {{ (is_array(request('tags')) && in_array($tag->id, request('tags', []))) || (is_string(request('tags')) && in_array($tag->id, explode(',', request('tags')))) ? 'checked' : '' }}
                                                onchange="document.getElementById('mobileFilterForm').submit()"
                                                class="rounded border-gray-300 text-orange-600 focus:ring-orange-500"
                                            >
                                            <span class="ml-2 text-sm text-gray-700">{{ $tag->icon }} {{ $tag->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                        <!-- Price Range -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">💰 Price Range</label>
                            <div class="flex items-center gap-2">
                                <input 
                                    type="number" 
                                    name="min_price" 
                                    value="{{ request('min_price') }}"
                                    placeholder="Min"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                                >
                                <span class="text-gray-500">-</span>
                                <input 
                                    type="number" 
                                    name="max_price" 
                                    value="{{ request('max_price') }}"
                                    placeholder="Max"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                                >
                            </div>
                        </div>
                        <!-- Rating Filter -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">⭐ Minimum Rating</label>
                            <select name="min_rating" onchange="document.getElementById('mobileFilterForm').submit()" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                                <option value="">Any Rating</option>
                                <option value="4" {{ request('min_rating') == '4' ? 'selected' : '' }}>4+ Stars</option>
                                <option value="3" {{ request('min_rating') == '3' ? 'selected' : '' }}>3+ Stars</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-orange-500 text-white py-2 px-4 rounded-md hover:bg-orange-600 transition">
                                Apply Filters
                            </button>
                    </form>
                </div>
            </div>
        </aside>
        <!-- Results Area -->
        <div class="w-full lg:w-3/4">
            <!-- Results Header -->
            <div class="mb-6 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900">
                    {{ $foodItems->total() }} food items available
                    @if(request('postcode'))
                        in {{ request('postcode') }}
                    @endif
                </h2>
            </div>
            <!-- Food Items Grid/List -->
            <div id="foodGrid" class="grid gap-6 {{ !request('view') || request('view')=='grid' ? 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4' : 'grid-cols-1' }}">
                @forelse($foodItems as $item)
                    <a href="{{ route('customers.food-item.show', $item) }}" class="block">
                        <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition-shadow duration-200 cursor-pointer relative group">
                            <!-- Badges -->
                            <div class="absolute top-3 left-3 flex flex-col gap-1 z-10">
                                @if($item->price < 10)
                                    <span class="badge bg-green-500 text-white px-2 py-1 rounded text-xs font-semibold">Great Value</span>
                                @endif
                                @if($item->provider && $item->provider->rating >= 4.7)
                                    <span class="badge bg-yellow-400 text-gray-900 px-2 py-1 rounded text-xs font-semibold">Top Rated</span>
                                @endif
                            </div>
                            <!-- Food Image -->
                            @php
                                $photos = $item->photos;
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
                                <img src="{{ $src }}" alt="{{ $item->title ?? 'Photo' }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-200">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400">No Photo</span>
                            </div>
                        @endif
                            <!-- Content -->
                        <div class="p-4">
                                <!-- Tags -->
                                <div class="flex flex-wrap gap-1 mb-2">
                                    @foreach($item->tags as $tag)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium" style="background: {{ $tag->color }}20; color: {{ $tag->color }};">
                                            {{ $tag->icon }} {{ $tag->name }}
                                </span>
                                    @endforeach
                                </div>
                                <!-- Title & Description -->
                                <h3 class="font-semibold text-lg text-gray-900 mb-1">{{ $item->title }}</h3>
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $item->description }}</p>
                                <!-- Provider Info -->
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="flex items-center">
                                        <span class="text-yellow-400">⭐</span>
                                        <span class="text-sm text-gray-600 ml-1">{{ $item->provider ? number_format($item->provider->rating, 1) : '-' }}</span>
                                    </div>
                                    <span class="text-gray-300">•</span>
                                    <span class="text-sm text-gray-600">{{ $item->provider ? $item->provider->name : 'Unknown' }}</span>
                                </div>
                                <!-- Price & Availability -->
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-bold text-gray-900">₹{{ number_format($item->price, 2) }}</span>
                                    <div class="text-right">
                                        <div class="text-sm text-gray-500">Available: {{ $item->available_date }}</div>
                                        <div class="text-sm text-green-600">{{ $item->available_quantity }} left</div>
                                    </div>
                            </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="text-gray-500 text-lg mb-2">🔍 No food items found</div>
                        <p class="text-gray-400">Try adjusting your filters or search terms</p>
                    </div>
                @endforelse
            </div>
            <!-- Pagination -->
            <div class="mt-8">
                {{ $foodItems->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <!-- Food Item Modal (Alpine.js) -->
    <div x-data="{ show: false, item: null }" x-show="show" @keydown.escape.window="show = false" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg max-w-lg w-full mx-4 relative" @click.away="show = false">
            <button @click="show = false" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            <template x-if="item">
                <div>
                    <img :src="item.photos && item.photos.length > 0 ? item.photos[0] : 'https://placehold.co/600x400/orange/fff?text=No+Photo'" alt="Food Photo" class="w-full h-64 object-cover rounded-t-lg">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold mb-2" x-text="item.title"></h2>
                        <div class="flex flex-wrap gap-2 mb-2">
                            <template x-for="tag in item.tags">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium" :style="'background: ' + tag.color + '20; color: ' + tag.color">
                                    <span x-text="tag.icon"></span> <span x-text="tag.name"></span>
                                </span>
                            </template>
                        </div>
                        <p class="text-gray-600 mb-4" x-text="item.description"></p>
                        <div class="flex items-center gap-4 mb-4">
                            <span class="text-lg font-bold text-gray-900" x-text="'₹' + Number(item.price).toFixed(2)"></span>
                            <span class="text-yellow-400">⭐</span>
                            <span class="text-sm text-gray-600" x-text="Number(item.provider.rating).toFixed(1)"></span>
                            <span class="text-gray-300">•</span>
                            <span class="text-sm text-gray-600" x-text="item.provider.name"></span>
                        </div>
                        <div class="mb-4">
                            <span class="text-sm text-gray-500">Available: <span x-text="item.available_date"></span></span>
                            <span class="ml-4 text-sm text-green-600" x-text="item.available_quantity + ' left'"></span>
                        </div>
                        <div class="flex gap-2">
                            <a :href="'/orders/create?food_item_id=' + item.id" class="flex-1 bg-orange-500 hover:bg-orange-600 text-white py-2 px-4 rounded-md text-center font-semibold">Book Item</a>
                            <template x-if="item.status === 'pending'">
                                <a :href="'/chat?order_id=' + item.id" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md text-center font-semibold">Chat</a>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    // Use precomputed food items data from controller
    window.foodItemsData = {!! $foodItemsJson !!};

    // View toggle
    function setView(view) {
        const url = new URL(window.location.href);
        url.searchParams.set('view', view);
        window.location = url.toString();
    }
    // Modal logic (Alpine.js)
    window.openFoodModal = function(id) {
        const items = window.foodItemsData;
        const item = items.find(i => i.id === id);
        if (!item) return;
        const modal = document.querySelector('[x-data]');
        modal.__x.$data.item = item;
        modal.__x.$data.show = true;
    }
</script>
<style>
    .badge { font-size: 0.8rem; font-weight: 500; padding: 0.25rem 0.5rem; border-radius: 4px; }
</style>
@endsection 