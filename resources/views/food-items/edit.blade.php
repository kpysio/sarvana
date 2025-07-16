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
                    {{-- Remove PHP debug output --}}
                    {{-- <div class="mb-4 p-2 bg-yellow-100 text-xs text-gray-700 rounded">Debug: Tags = <pre>@json($tags)</pre></div> --}}
                    <form method="POST" action="{{ auth()->user() && auth()->user()->user_type === 'provider' ? route('provider.food-items.update', $foodItem) : route('food-items.update', $foodItem) }}" enctype="multipart/form-data">
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
                                <x-input-label for="price" :value="__('Price (£)')" />
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
                                <x-input-label for="available_time" :value="__('Ready for Pickup Time')" />
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
                                            @php
                                                $isUrl = Str::startsWith($photo, ['http://', 'https://']);
                                                $src = $isUrl ? $photo : asset('storage/' . ltrim($photo, '/'));
                                            @endphp
                                            <div class="relative">
                                                <img src="{{ $src }}" alt="Food item photo" class="w-full h-24 object-cover rounded-lg">
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

                            <!-- Order Type (Provider Only) -->
                            @if(auth()->user() && auth()->user()->user_type === 'provider')
                                <div class="md:col-span-2">
                                    <x-input-label for="order_type" :value="__('Order Type')" />
                                    <select id="order_type" name="order_type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="daily" {{ old('order_type', $foodItem->order_type ?? 'daily') == 'daily' ? 'selected' : '' }}>Daily (expires end of day)</option>
                                        <option value="subscription" {{ old('order_type', $foodItem->order_type ?? '') == 'subscription' ? 'selected' : '' }}>Subscription</option>
                                        <option value="custom" {{ old('order_type', $foodItem->order_type ?? '') == 'custom' ? 'selected' : '' }}>Custom</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('order_type')" class="mt-2" />
                                </div>
                            @endif

                            <!-- Tag Selector (Dual List Drag-and-Drop) -->
                            <div class="md:col-span-2">
                                <x-input-label for="tags" :value="__('Tags')" />
                                <div x-data='dualTagSelector({ allTags: @json($tags), selected: @json($foodItem->tags->toArray()), search: "" })' class="mt-1 flex flex-col md:flex-row gap-4">
                                    {{-- Remove Alpine.js debug output for groupedFilteredTags --}}
                                    <!-- Available Tags -->
                                    <div class="flex-1 min-w-[220px]">
                                        <div class="font-semibold text-gray-700 mb-2">Available Tags</div>
                                        <input type="text" x-model="search" placeholder="Search tags..." class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm mb-2">
                                        <div class="bg-gray-50 border rounded-md p-2 min-h-[120px] max-h-64 overflow-auto" id="available-tags">
                                            <template x-for="(tags, category) in groupedFilteredTags" :key="category">
                                                <div>
                                                    <div class="px-2 py-1 text-xs font-semibold text-gray-500 bg-gray-100" x-text="category"></div>
                                                    <template x-for="tag in tags" :key="tag.id">
                                                        <div class="draggable-tag flex items-center gap-2 px-2 py-1 my-1 rounded cursor-move bg-white border hover:bg-blue-50"
                                                            :style="tag.color ? 'border-left: 4px solid ' + tag.color : ''"
                                                            :data-id="tag.id"
                                                            @click="addTag(tag)">
                                                            <span class="w-2 h-2 rounded-full inline-block" :style="'background:' + tag.color"></span>
                                                            <span x-text="tag.icon"></span>
                                                            <span x-text="tag.name"></span>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    <!-- Applied Tags -->
                                    <div class="flex-1 min-w-[220px]">
                                        <div class="font-semibold text-gray-700 mb-2">Applied Tags</div>
                                        <div class="bg-gray-50 border rounded-md p-2 min-h-[120px] max-h-64 overflow-auto" id="applied-tags">
                                            <template x-for="tag in selectedTags" :key="tag.id">
                                                <div class="draggable-tag flex items-center gap-2 px-2 py-1 my-1 rounded cursor-move bg-blue-100 border border-blue-200 text-blue-800"
                                                    :style="tag.color ? 'border-left: 4px solid ' + tag.color + '; color:' + tag.color : ''"
                                                    :data-id="tag.id"
                                                    @click="removeTag(tag)">
                                                    <span class="w-2 h-2 rounded-full inline-block" :style="'background:' + tag.color"></span>
                                                    <span x-text="tag.icon"></span>
                                                    <span x-text="tag.name"></span>
                                                    <input type="hidden" name="tags[]" :value="tag.id">
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('tags')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ auth()->user() && auth()->user()->user_type === 'provider' ? route('provider.food-items.show', $foodItem) : route('food-items.show', $foodItem) }}" class="text-gray-600 hover:text-gray-800 mr-4">
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

{{-- Ensure Alpine.js is loaded before this script --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
{{-- If Alpine.js is not already loaded globally, add it here: --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
window.dualTagSelector = function({ allTags, selected = [], search = '' }) {
    return {
        allTags,
        selectedTags: selected,
        search,
        get groupedFilteredTags() {
            const groups = {};
            const search = (this.search || '').toLowerCase().trim();
            this.allTags.forEach(tag => {
                if (!tag.name) return;
                if (this.selectedTags.some(t => t.id === tag.id)) return;
                if (
                    search &&
                    !(
                        tag.name.toLowerCase().includes(search) ||
                        (tag.icon && tag.icon.toLowerCase().includes(search)) ||
                        (tag.category && tag.category.toLowerCase().includes(search))
                    )
                ) return;
                if (!groups[tag.category]) groups[tag.category] = [];
                groups[tag.category].push(tag);
            });
            return groups;
        },
        addTag(tag) {
            if (!this.selectedTags.some(t => t.id === tag.id)) {
                this.selectedTags.push(tag);
            }
        },
        removeTag(tag) {
            this.selectedTags = this.selectedTags.filter(t => t.id !== tag.id);
        },
        init() {
            const self = this;
            // Available Tags Drag
            new Sortable(document.getElementById('available-tags'), {
                group: {
                    name: 'tags',
                    pull: 'clone',
                    put: false
                },
                sort: false,
                animation: 150,
                onEnd(evt) {
                    const tagId = evt.item.getAttribute('data-id');
                    const tag = self.allTags.find(t => t.id == tagId);
                    if (tag) self.addTag(tag);
                }
            });
            // Applied Tags Drag
            new Sortable(document.getElementById('applied-tags'), {
                group: {
                    name: 'tags',
                    pull: true,
                    put: true
                },
                animation: 150,
                onEnd(evt) {
                    if (evt.to === evt.from && evt.oldIndex !== evt.newIndex) {
                        // Reorder within applied tags
                        const moved = self.selectedTags.splice(evt.oldIndex, 1)[0];
                        self.selectedTags.splice(evt.newIndex, 0, moved);
                    } else if (evt.from.id === 'applied-tags' && evt.to.id === 'available-tags') {
                        // Remove from applied tags
                        const tagId = evt.item.getAttribute('data-id');
                        self.removeTag(self.selectedTags.find(t => t.id == tagId));
                    }
                }
            });
        }
    };
}
document.addEventListener('alpine:init', () => {
    Alpine.data('dualTagSelector', window.dualTagSelector);
});
</script> 