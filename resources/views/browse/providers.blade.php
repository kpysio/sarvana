<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Food Providers') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('browse.providers') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                   class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        </div>
                        
                        <div>
                            <label for="min_rating" class="block text-sm font-medium text-gray-700">Minimum Rating</label>
                            <select name="min_rating" id="min_rating" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Any Rating</option>
                                <option value="4" {{ request('min_rating') == '4' ? 'selected' : '' }}>4+ Stars</option>
                                <option value="3" {{ request('min_rating') == '3' ? 'selected' : '' }}>3+ Stars</option>
                                <option value="2" {{ request('min_rating') == '2' ? 'selected' : '' }}>2+ Stars</option>
                            </select>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                Apply Filters
                            </button>
                            <a href="{{ route('browse.providers') }}" class="ml-2 text-gray-600 hover:text-gray-800">Clear Filters</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Providers Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($providers as $provider)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                @if($provider->profile_photo)
                                    <img src="{{ Storage::url($provider->profile_photo) }}" alt="{{ $provider->name }}" 
                                         class="h-12 w-12 rounded-full object-cover">
                                @else
                                    <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-400 text-sm">{{ substr($provider->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $provider->name }}</h3>
                                    <div class="flex items-center">
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $provider->rating)
                                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-sm text-gray-600 ml-2">{{ $provider->rating }}/5 ({{ $provider->total_reviews }} reviews)</span>
                                    </div>
                                </div>
                            </div>

                            @if($provider->bio)
                                <p class="text-gray-600 mb-4">{{ Str::limit($provider->bio, 100) }}</p>
                            @endif

                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <span>{{ $provider->food_items_count }} items available</span>
                                @if($provider->is_verified)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Verified
                                    </span>
                                @endif
                            </div>

                            <div class="flex space-x-2">
                                <a href="{{ route('browse.provider', $provider) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded-lg">
                                    View Items
                                </a>
                                <button class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-lg">
                                    Follow
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500">No providers found matching your criteria.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($providers->hasPages())
                <div class="mt-6">
                    {{ $providers->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 