<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Browse Food Items') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('browse.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                            <select name="category" id="category" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                        {{ ucfirst($category) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="min_price" class="block text-sm font-medium text-gray-700">Min Price</label>
                            <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        
                        <div>
                            <label for="max_price" class="block text-sm font-medium text-gray-700">Max Price</label>
                            <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        
                        <div class="md:col-span-4">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                Apply Filters
                            </button>
                            <a href="{{ route('browse.index') }}" class="ml-2 text-gray-600 hover:text-gray-800">Clear Filters</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Food Items Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($foodItems as $item)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        @if($item->photos && is_array($item->photos) && count($item->photos) > 0)
                            <img src="{{ $item->photos[0] }}" alt="{{ $item->title }}" 
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">No Image</span>
                            </div>
                        @endif
                        
                        <div class="p-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ $item->title }}</h3>
                            <p class="text-sm text-gray-600">{{ $item->category }}</p>
                            <p class="text-sm text-gray-600">by {{ $item->provider->name }}</p>
                            <p class="text-lg font-bold text-green-600 mt-2">₹{{ $item->price }}</p>
                            
                            <div class="mt-2 flex items-center justify-between">
                                <span class="text-sm text-gray-500">
                                    Available: {{ $item->available_quantity }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $item->status === 'active' ? 'bg-green-100 text-green-800' : 
                                       ($item->status === 'inactive' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>
                            
                            <div class="mt-4">
                                <a href="{{ route('browse.show', $item) }}" 
                                   class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded-lg">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500">No food items found matching your criteria.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($foodItems->hasPages())
                <div class="mt-6">
                    {{ $foodItems->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 