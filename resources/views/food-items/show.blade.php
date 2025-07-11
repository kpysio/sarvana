<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $foodItem->title ?? '[No Title]' }}
            </h2>
            <div class="flex space-x-2">
                @if(auth()->check() && isset($foodItem->provider_id) && auth()->id() === $foodItem->provider_id)
                    <a href="{{ route('food-items.edit', $foodItem) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Edit
                    </a>
                @endif
                <a href="{{ url('/search') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    @if(!$foodItem || empty($foodItem->title))
        <div class="max-w-2xl mx-auto mt-10 bg-red-100 text-red-700 p-4 rounded-lg">
            <strong>Debug:</strong> Food item data is missing or incomplete.<br>
            <pre>{{ var_export($foodItem, true) }}</pre>
        </div>
    @endif

    <!-- Advanced UI/UX Food Item Detail -->
    <div class="py-8 bg-gradient-to-br from-orange-100 via-yellow-50 to-pink-100 min-h-screen">
        <div class="max-w-3xl mx-auto rounded-2xl shadow-xl overflow-hidden bg-white">
            <!-- Photo Gallery -->
            <div class="relative">
                @php
                    $photos = $foodItem->photos;
                    if (is_string($photos)) {
                        $photos = json_decode($photos, true);
                    }
                    if (!is_array($photos)) {
                        $photos = [];
                    }
                @endphp
                <div class="flex overflow-x-auto snap-x snap-mandatory">
                    @if(count($photos) > 0)
                        @foreach($photos as $i => $photo)
                            @php
                                $isUrl = Str::startsWith($photo, ['http://', 'https://']);
                                $src = $isUrl ? $photo : asset('storage/' . ltrim($photo, '/'));
                            @endphp
                            <img src="{{ $src }}" alt="{{ $foodItem->title ?? 'Photo' }}" class="w-full h-72 object-cover snap-center transition-transform duration-300">
                        @endforeach
                    @else
                        <div class="w-full h-72 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400">No Photos Available</span>
                        </div>
                    @endif
                </div>
                <!-- Gallery indicators -->
                @if(count($photos) > 1)
                    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                        @foreach($photos as $i => $photo)
                            <span class="w-3 h-3 rounded-full bg-white border border-gray-400 {{ $i === 0 ? 'ring-2 ring-orange-400' : '' }}"></span>
                        @endforeach
                    </div>
                @endif
                <!-- Favorite Button -->
                <button class="absolute top-4 right-4 bg-white rounded-full shadow p-2 hover:scale-110 transition-all">
                    <svg class="w-7 h-7 text-pink-500 animate-pulse" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
                </button>
                <!-- Offer Badge -->
                <div class="absolute top-4 left-4 bg-gradient-to-r from-pink-500 to-orange-400 text-white px-4 py-1 rounded-full font-bold shadow-lg animate-bounce">
                    Buy 1 Get 1 FREE
                </div>
            </div>
            <!-- Details Section -->
            <div class="p-6 md:p-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                    <div>
                        <h1 class="text-3xl font-extrabold text-gray-900 mb-2">{{ $foodItem->title ?? '[No Title]' }}</h1>
                        <div class="flex items-center space-x-2">
                            <span class="text-lg text-green-600 font-bold">₹{{ $foodItem->price ?? 'N/A' }}</span>
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">{{ $foodItem->available_quantity ?? 0 }} available</span>
                        </div>
                    </div>
                    <!-- Order Deadline Timer -->
                    <div class="flex flex-col items-end">
                        <span class="text-xs text-gray-500">Order Deadline</span>
                        <span id="deadline-timer" class="text-lg font-bold text-orange-600">2h 30m left</span>
                    </div>
                </div>
                <p class="text-gray-700 mb-4">{{ $foodItem->description ?? '' }}</p>
                <div class="flex flex-wrap gap-2 mb-4">
                    @if(isset($foodItem->tags) && count($foodItem->tags) > 0)
                        @foreach($foodItem->tags as $tag)
                            <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-semibold">{{ $tag->name ?? $tag }}</span>
                        @endforeach
                    @endif
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <span class="block text-xs text-gray-500">Pickup Address</span>
                        <span class="block text-gray-900 font-medium">{{ $foodItem->pickup_address ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Available</span>
                        <span class="block text-gray-900 font-medium">
                            @if(isset($foodItem->available_date) && $foodItem->available_date)
                                {{ $foodItem->available_date->format('M d, Y') }}
                            @else
                                N/A
                            @endif
                            at
                            @if(isset($foodItem->available_time) && $foodItem->available_time)
                                {{ \Carbon\Carbon::parse($foodItem->available_time)->format('g:i A') }}
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                </div>
                <!-- Provider Info -->
                <div class="flex items-center space-x-4 mb-6">
                    <img src="https://api.dicebear.com/7.x/initials/svg?seed={{ urlencode($foodItem->provider->name ?? 'Provider') }}" class="w-12 h-12 rounded-full border-2 border-orange-400" alt="Provider">
                    <div>
                        <div class="font-bold text-gray-800">{{ $foodItem->provider->name ?? 'Provider' }}</div>
                        <div class="text-xs text-gray-500">{{ $foodItem->provider->bio ?? 'Home Cook' }}</div>
                        <div class="flex items-center text-yellow-500">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.175 0l-3.385 2.46c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.045 9.394c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.286-3.967z"/></svg>
                            <span class="font-semibold">{{ number_format($foodItem->provider->rating ?? 4.7, 1) }}</span>
                        </div>
                    </div>
                </div>
                <!-- Real-time Availability -->
                <div class="mb-6">
                    <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold animate-pulse">{{ $foodItem->available_quantity ?? 0 }} items available</span>
                </div>
                <!-- Order Form for Customers -->
                @if(auth()->user() && method_exists(auth()->user(), 'isCustomer') && auth()->user()->isCustomer())
                    <form action="{{ route('orders.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="food_item_id" value="{{ $foodItem->id }}">
                        <input type="hidden" name="provider_id" value="{{ $foodItem->provider_id }}">
                        {{-- Order type is set by provider, not selectable by customer --}}
                        {{-- <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Order Type</label>
                            <select name="order_type" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                                <option value="daily">Daily (one-time)</option>
                                <option value="subscription">Subscription</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div> --}}
                        {{-- Remove these fields since order type is not selectable by customer --}}
                        {{--
                        <div class="hidden" id="subscriptionDaysField">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subscription Days</label>
                            <input type="number" name="subscription_days" min="1" max="30" class="w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div class="hidden" id="customDetailsField">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Custom Order Details</label>
                            <textarea name="custom_details" rows="2" class="w-full border border-gray-300 rounded-md px-3 py-2"></textarea>
                        </div>
                        --}}
                        <div class="flex items-center space-x-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                            <button type="button" class="decrement bg-gray-200 px-2 py-1 rounded-full text-lg">-</button>
                            <input type="number" name="quantity" min="1" max="{{ $foodItem->available_quantity ?? 1 }}" value="1" class="w-16 border border-gray-300 rounded-md px-3 py-2 text-center" required>
                            <button type="button" class="increment bg-gray-200 px-2 py-1 rounded-full text-lg">+</button>
                            <span class="text-xs text-gray-500">Available: {{ $foodItem->available_quantity ?? 0 }}</span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pickup Time</label>
                            <input type="datetime-local" name="pickup_time" class="w-full border border-gray-300 rounded-md px-3 py-2" required value="{{ isset($foodItem->available_date) && $foodItem->available_date ? $foodItem->available_date->format('Y-m-d') : '' }}T{{ isset($foodItem->available_time) && $foodItem->available_time ? $foodItem->available_time : '' }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Special Instructions</label>
                            <textarea name="customer_notes" rows="2" class="w-full border border-gray-300 rounded-md px-3 py-2"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-pink-500 hover:from-orange-600 hover:to-pink-600 text-white font-semibold py-2 px-4 rounded-md shadow-lg transition-all">Place Order</button>
                    </form>
                    <script>
                        // Show/hide subscription/custom fields
                        document.addEventListener('DOMContentLoaded', function() {
                            const orderType = document.querySelector('select[name=order_type]');
                            const subField = document.getElementById('subscriptionDaysField');
                            const customField = document.getElementById('customDetailsField');
                            orderType.addEventListener('change', function() {
                                subField.classList.toggle('hidden', this.value !== 'subscription');
                                customField.classList.toggle('hidden', this.value !== 'custom');
                            });
                            // Quantity increment/decrement
                            const qtyInput = document.querySelector('input[name=quantity]');
                            document.querySelector('.increment').onclick = () => {
                                let v = parseInt(qtyInput.value); if (v < {{ $foodItem->available_quantity ?? 1 }}) qtyInput.value = v+1;
                            };
                            document.querySelector('.decrement').onclick = () => {
                                let v = parseInt(qtyInput.value); if (v > 1) qtyInput.value = v-1;
                            };
                        });
                    </script>
                @endif
                <!-- Reviews Section -->
                <div class="mt-8">
                    <h3 class="text-lg font-bold mb-2">Customer Reviews</h3>
                    <div class="space-y-4">
                        @if(isset($foodItem->reviews) && count($foodItem->reviews) > 0)
                            @foreach($foodItem->reviews as $review)
                                <div class="bg-gray-50 p-4 rounded-lg shadow flex items-start space-x-3">
                                    <img src="https://api.dicebear.com/7.x/initials/svg?seed={{ urlencode($review->reviewer->name ?? 'C') }}" class="w-8 h-8 rounded-full" alt="Reviewer">
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <span class="font-semibold text-gray-800">{{ $review->reviewer->name ?? 'Customer' }}</span>
                                            <span class="text-yellow-500 flex items-center">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.175 0l-3.385 2.46c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.045 9.394c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.286-3.967z"/></svg>
                                                <span class="ml-1 font-bold">{{ $review->rating ?? 5 }}</span>
                                            </span>
                                        </div>
                                        <div class="text-gray-600 text-sm mt-1">{{ $review->comment ?? '' }}</div>
                                        @if(isset($review->photo) && $review->photo)
                                            @php
                                                $isUrl = Str::startsWith($review->photo, ['http://', 'https://']);
                                                $src = $isUrl ? $review->photo : asset('storage/' . ltrim($review->photo, '/'));
                                            @endphp
                                            <img src="{{ $src }}" class="w-20 h-20 object-cover rounded mt-2">
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-gray-400">No reviews yet.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Countdown timer for order deadline (example: 2h 30m left)
        document.addEventListener('DOMContentLoaded', function() {
            const timer = document.getElementById('deadline-timer');
            // Example: deadline is 2.5 hours from now
            let deadline = new Date();
            deadline.setHours(deadline.getHours() + 2, deadline.getMinutes() + 30, 0, 0);
            function updateTimer() {
                let now = new Date();
                let diff = deadline - now;
                if (diff <= 0) { timer.textContent = 'Order closed'; return; }
                let h = Math.floor(diff / 1000 / 60 / 60);
                let m = Math.floor((diff / 1000 / 60) % 60);
                timer.textContent = `${h}h ${m}m left`;
            }
            setInterval(updateTimer, 1000);
            updateTimer();
        });
    </script>
</x-app-layout> 