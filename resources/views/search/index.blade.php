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
            <!-- Main Content Area -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-8">
                <!-- Sticky Filters Sidebar (desktop only) -->
                <aside class="hidden lg:block lg:w-1/4 flex-shrink-0 sticky top-24 self-start z-20">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Filters</h3>
                        <form method="GET" action="{{ route('search.index') }}" id="filterForm">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <!-- Location Filter -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">📍 Location</label>
                                <select name="postcode" onchange="document.getElementById('filterForm').submit()" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
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
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
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
                                                    class="rounded border-gray-300 dark:border-gray-600 text-orange-600 focus:ring-orange-500 bg-white dark:bg-gray-700"
                                                >
                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $tag->icon }} {{ $tag->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                            <!-- Price Range -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">💰 Price Range</label>
                                <div class="flex items-center gap-2">
                                    <input 
                                        type="number" 
                                        name="min_price" 
                                        value="{{ request('min_price') }}"
                                        placeholder="Min"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                    >
                                    <span class="text-gray-500 dark:text-gray-400">-</span>
                                    <input 
                                        type="number" 
                                        name="max_price" 
                                        value="{{ request('max_price') }}"
                                        placeholder="Max"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                    >
                                </div>
                            </div>
                            <!-- Rating Filter -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">⭐ Minimum Rating</label>
                                <select name="min_rating" onchange="document.getElementById('filterForm').submit()" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
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
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                        <button type="button" class="w-full flex items-center justify-between px-4 py-2 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm mb-4" onclick="document.getElementById('mobileFiltersPanel').classList.toggle('hidden')">
                            <span class="font-semibold text-gray-900 dark:text-white">Filters</span>
                            <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="mobileFiltersPanel" class="hidden">
                            <form method="GET" action="{{ route('search.index') }}" id="mobileFilterForm">
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <!-- (Repeat all filter fields as above for mobile) -->
                                <!-- Location Filter -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">📍 Location</label>
                                    <select name="postcode" onchange="document.getElementById('mobileFilterForm').submit()" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
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
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
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
                                                        class="rounded border-gray-300 dark:border-gray-600 text-orange-600 focus:ring-orange-500 bg-white dark:bg-gray-700"
                                                    >
                                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $tag->icon }} {{ $tag->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                                <!-- Price Range -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">💰 Price Range</label>
                                    <div class="flex items-center gap-2">
                                        <input 
                                            type="number" 
                                            name="min_price" 
                                            value="{{ request('min_price') }}"
                                            placeholder="Min"
                                            class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                        >
                                        <span class="text-gray-500 dark:text-gray-400">-</span>
                                        <input 
                                            type="number" 
                                            name="max_price" 
                                            value="{{ request('max_price') }}"
                                            placeholder="Max"
                                            class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                        >
                                    </div>
                                </div>
                                <!-- Rating Filter -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">⭐ Minimum Rating</label>
                                    <select name="min_rating" onchange="document.getElementById('mobileFilterForm').submit()" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
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
                    <!-- Header: Search, Pills, Sort, View Toggle (moved here) -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <!-- Search Bar -->
                            <div class="flex-1 max-w-2xl">
                                <form method="GET" action="{{ route('search.index') }}" class="relative">
                                    <input 
                                        type="text" 
                                        name="search" 
                                        value="{{ request('search') }}"
                                        placeholder="Search for biryani, thepla, dosa..."
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                    >
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <!-- View Toggle -->
                                <div class="flex items-center ml-2">
                                    <button id="gridBtn" class="view-btn px-2 py-1 rounded {{ !request('view') || request('view')=='grid' ? 'bg-white dark:bg-gray-700 shadow' : '' }}" onclick="setView('grid')">⊞</button>
                                    <button id="listBtn" class="view-btn px-2 py-1 rounded {{ request('view')=='list' ? 'bg-white dark:bg-gray-700 shadow' : '' }}" onclick="setView('list')">☰</button>
                                </div>
                                <!-- Sort -->
                                <form method="GET" action="{{ route('search.index') }}" class="ml-2">
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                    <select name="sort" onchange="this.form.submit()" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Latest</option>
                                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Results Header -->
                    <div class="mb-6 flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
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
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow duration-200 cursor-pointer relative group">
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
                                        $photos = $item->photos ?? [];
                                        if (is_string($photos)) {
                                            $photos = json_decode($photos, true);
                                        }
                                        if (!is_array($photos)) {
                                            $photos = [];
                                        }
                                    @endphp
                                    <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-t-lg bg-gray-200 dark:bg-gray-700">
                                        @if(count($photos) > 0)
                                            @php
                                                $photo = $photos[0];
                                                $isUrl = Str::startsWith($photo, ['http://', 'https://']);
                                                $src = $isUrl ? $photo : asset('storage/' . ltrim($photo, '/'));
                                            @endphp
                                            <img src="{{ $src }}" alt="{{ $item->title }}" class="h-full w-full object-cover object-center group-hover:opacity-75">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center">
                                                <span class="text-gray-400 dark:text-gray-500 text-sm">No Image</span>
                                            </div>
                                        @endif
                                    </div>
                                    <!-- Food Details -->
                                    <div class="p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->title }}</h3>
                                            <p class="text-sm font-bold text-orange-600 dark:text-orange-400">₹{{ number_format($item->price, 2) }}</p>
                                        </div>
                                        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-2">
                                            <span>{{ $item->provider->name ?? 'Unknown Provider' }}</span>
                                            @if($item->provider && $item->provider->rating)
                                                <span class="flex items-center">
                                                    <span class="text-yellow-400 mr-1">⭐</span>
                                                    {{ number_format($item->provider->rating, 1) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                            <span>{{ $item->available_quantity }} left</span>
                                            <span>{{ $item->available_date ? \Carbon\Carbon::parse($item->available_date)->format('M d') : 'N/A' }}</span>
                                        </div>
                                        <!-- Tags -->
                                        @if($item->tags && count($item->tags) > 0)
                                            <div class="mt-2 flex flex-wrap gap-1">
                                                @foreach($item->tags->take(2) as $tag)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium" style="background: {{ $tag->color ?? '#f59e42' }}20; color: {{ $tag->color ?? '#f59e42' }};">
                                                        {{ $tag->icon ?? '' }} {{ $tag->name }}
                                                    </span>
                                                @endforeach
                                                @if($item->tags->count() > 2)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                                        +{{ $item->tags->count() - 2 }} more
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No food items found</h3>
                                    <p class="text-gray-500 dark:text-gray-400">Try adjusting your search criteria or filters.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <!-- Pagination -->
                    @if($foodItems->hasPages())
                        <div class="mt-8">
                            {{ $foodItems->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </main>

        <script>
            function setView(view) {
                const url = new URL(window.location);
                url.searchParams.set('view', view);
                window.location.href = url.toString();
            }
        </script>
    </body>
</html> 