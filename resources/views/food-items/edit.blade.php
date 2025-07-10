<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Food Item') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('food-items.update', $foodItem) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Title -->
                            <div class="md:col-span-2">
                                <x-input-label for="title" :value="__('Title')" />
                                <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $foodItem->title)" required />
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <x-input-label for="description" :value="__('Description')" />
                                <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('description', $foodItem->description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <!-- Category -->
                            <div>
                                <x-input-label for="category" :value="__('Category')" />
                                <select id="category" name="category" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select Category</option>
                                    <option value="burger" {{ old('category', $foodItem->category) == 'burger' ? 'selected' : '' }}>Burger</option>
                                    <option value="tiffin" {{ old('category', $foodItem->category) == 'tiffin' ? 'selected' : '' }}>Tiffin</option>
                                    <option value="cake" {{ old('category', $foodItem->category) == 'cake' ? 'selected' : '' }}>Cake</option>
                                    <option value="snacks" {{ old('category', $foodItem->category) == 'snacks' ? 'selected' : '' }}>Snacks</option>
                                    <option value="biryani" {{ old('category', $foodItem->category) == 'biryani' ? 'selected' : '' }}>Biryani</option>
                                    <option value="sweets" {{ old('category', $foodItem->category) == 'sweets' ? 'selected' : '' }}>Sweets</option>
                                    <option value="other" {{ old('category', $foodItem->category) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                <x-input-error :messages="$errors->get('category')" class="mt-2" />
                            </div>

                            <!-- Price -->
                            <div>
                                <x-input-label for="price" :value="__('Price (₹)')" />
                                <x-text-input id="price" class="block mt-1 w-full" type="number" name="price" :value="old('price', $foodItem->price)" step="0.01" min="0" required />
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>

                            <!-- Available Quantity -->
                            <div>
                                <x-input-label for="available_quantity" :value="__('Available Quantity')" />
                                <x-text-input id="available_quantity" class="block mt-1 w-full" type="number" name="available_quantity" :value="old('available_quantity', $foodItem->available_quantity)" min="0" required />
                                <x-input-error :messages="$errors->get('available_quantity')" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="active" {{ old('status', $foodItem->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $foodItem->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="sold_out" {{ old('status', $foodItem->status) == 'sold_out' ? 'selected' : '' }}>Sold Out</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <!-- Available Date -->
                            <div>
                                <x-input-label for="available_date" :value="__('Available Date')" />
                                <x-text-input id="available_date" class="block mt-1 w-full" type="date" name="available_date" :value="old('available_date', $foodItem->available_date->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('available_date')" class="mt-2" />
                            </div>

                            <!-- Available Time -->
                            <div>
                                <x-input-label for="available_time" :value="__('Available Time')" />
                                <x-text-input id="available_time" class="block mt-1 w-full" type="time" name="available_time" :value="old('available_time', \Carbon\Carbon::parse($foodItem->available_time)->format('H:i'))" required />
                                <x-input-error :messages="$errors->get('available_time')" class="mt-2" />
                            </div>

                            <!-- Pickup Address -->
                            <div class="md:col-span-2">
                                <x-input-label for="pickup_address" :value="__('Pickup Address')" />
                                <textarea id="pickup_address" name="pickup_address" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('pickup_address', $foodItem->pickup_address) }}</textarea>
                                <x-input-error :messages="$errors->get('pickup_address')" class="mt-2" />
                            </div>

                            <!-- Current Photos -->
                            @if($foodItem->photos && is_array($foodItem->photos) && count($foodItem->photos) > 0)
                                <div class="md:col-span-2">
                                    <x-input-label :value="__('Current Photos')" />
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-2">
                                        @foreach($foodItem->photos as $photo)
                                            <div class="relative">
                                                <img src="{{ $photo }}" alt="Food item photo" class="w-full h-24 object-cover rounded-lg">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- New Photos -->
                            <div class="md:col-span-2">
                                <x-input-label for="photos" :value="__('Add New Photos')" />
                                <input type="file" id="photos" name="photos[]" multiple accept="image/*" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                                <p class="text-sm text-gray-500 mt-1">You can select multiple photos. Maximum 5MB each.</p>
                                <x-input-error :messages="$errors->get('photos.*')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('food-items.show', $foodItem) }}" class="text-gray-600 hover:text-gray-800 mr-4">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Update Food Item') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 