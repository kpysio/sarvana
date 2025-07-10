<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $provider->name }}'s Food Items
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Provider Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center">
                        @if($provider->profile_photo)
                            <img src="{{ Storage::url($provider->profile_photo) }}" alt="{{ $provider->name }}" 
                                 class="h-16 w-16 rounded-full object-cover">
                        @else
                            <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400 text-lg">{{ substr($provider->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div class="ml-4">
                            <h3 class="text-xl font-medium text-gray-900">{{ $provider->name }}</h3>
                            <div class="flex items-center mt-1">
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
                                @if($provider->is_verified)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2">
                                        Verified
                                    </span>
                                @endif
                            </div>
                            @if($provider->bio)
                                <p class="text-gray-600 mt-2">{{ $provider->bio }}</p>
                            @endif
                        </div>
                    </div>
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
                            <h4 class="text-lg font-medium text-gray-900">{{ $item->title }}</h4>
                            <p class="text-sm text-gray-600">{{ $item->category }}</p>
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
                        <p class="text-gray-500">No food items available from this provider.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($foodItems->hasPages())
                <div class="mt-6">
                    {{ $foodItems->links() }}
                </div>
            @endif

            <!-- Reviews -->
            @if($reviews->count() > 0)
                <div class="mt-12">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Reviews</h3>
                    <div class="space-y-4">
                        @foreach($reviews as $review)
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                                <div class="flex items-start">
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
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
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900">{{ $review->reviewer->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $review->created_at->format('M d, Y') }}</p>
                                        </div>
                                        @if($review->comment)
                                            <p class="text-gray-600 mt-1">{{ $review->comment }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 