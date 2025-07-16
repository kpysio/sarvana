@extends('layouts.provider')

@section('content')
<div>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-4">
            <form method="POST" action="{{ auth()->user() && auth()->user()->user_type === 'provider' ? route('provider.food-items.store') : route('food-items.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', isset($clone) ? $clone->title : '')" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>
                    <!-- Description -->
                    <div class="md:col-span-2">
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('description', isset($clone) ? $clone->description : '') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                    <!-- Category -->
                    <div>
                        <x-input-label for="category" :value="__('Category')" />
                        <select id="category" name="category" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            <option value="">Select Category</option>
                            <option value="burger" {{ old('category', isset($clone) ? $clone->category : '') == 'burger' ? 'selected' : '' }}>Burger</option>
                            <option value="tiffin" {{ old('category', isset($clone) ? $clone->category : '') == 'tiffin' ? 'selected' : '' }}>Tiffin</option>
                            <option value="cake" {{ old('category', isset($clone) ? $clone->category : '') == 'cake' ? 'selected' : '' }}>Cake</option>
                            <option value="snacks" {{ old('category', isset($clone) ? $clone->category : '') == 'snacks' ? 'selected' : '' }}>Snacks</option>
                            <option value="biryani" {{ old('category', isset($clone) ? $clone->category : '') == 'biryani' ? 'selected' : '' }}>Biryani</option>
                            <option value="sweets" {{ old('category', isset($clone) ? $clone->category : '') == 'sweets' ? 'selected' : '' }}>Sweets</option>
                            <option value="other" {{ old('category', isset($clone) ? $clone->category : '') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        <x-input-error :messages="$errors->get('category')" class="mt-2" />
                    </div>
                    <!-- Price -->
                    <div>
                        <x-input-label for="price" :value="__('Price (£)')" />
                        <x-text-input id="price" class="block mt-1 w-full" type="number" name="price" :value="old('price', isset($clone) ? $clone->price : '')" step="0.01" min="0" required />
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </div>
                    <!-- Available Quantity -->
                    <div>
                        <x-input-label for="available_quantity" :value="__('Available Quantity')" />
                        <x-text-input id="available_quantity" class="block mt-1 w-full" type="number" name="available_quantity" :value="old('available_quantity', isset($clone) ? $clone->available_quantity : '')" min="1" required />
                        <x-input-error :messages="$errors->get('available_quantity')" class="mt-2" />
                    </div>
                    <!-- Available Date -->
                    <div>
                        <x-input-label for="available_date" :value="__('Available Date')" />
                        <x-text-input id="available_date" class="block mt-1 w-full" type="date" name="available_date" :value="old('available_date', isset($clone) && $clone->available_date ? $clone->available_date->format('Y-m-d') : date('Y-m-d'))" required />
                        <x-input-error :messages="$errors->get('available_date')" class="mt-2" />
                    </div>
                    <!-- Available Time -->
                    <div>
                        <x-input-label for="available_time" :value="__('Ready for Pickup Time')" />
                        <x-text-input id="available_time" class="block mt-1 w-full" type="time" name="available_time" :value="old('available_time', isset($clone) && $clone->available_time ? \Carbon\Carbon::parse($clone->available_time)->format('H:i') : date('H:i'))" required />
                        <x-input-error :messages="$errors->get('available_time')" class="mt-2" />
                    </div>
                    <!-- Pickup Address -->
                    <div class="md:col-span-2">
                        <x-input-label for="pickup_address" :value="__('Pickup Address')" />
                        <textarea id="pickup_address" name="pickup_address" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('pickup_address', isset($clone) ? $clone->pickup_address : '') }}</textarea>
                        <x-input-error :messages="$errors->get('pickup_address')" class="mt-2" />
                    </div>
                    <!-- Order Type (Provider Only) -->
                    @if(auth()->user() && auth()->user()->user_type === 'provider')
                        <div class="md:col-span-2">
                            <x-input-label for="order_type" :value="__('Order Type')" />
                            <select id="order_type" name="order_type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="daily" {{ old('order_type', isset($clone) ? $clone->order_type : 'daily') == 'daily' ? 'selected' : '' }}>Daily (expires end of day)</option>
                                <option value="subscription" {{ old('order_type', isset($clone) ? $clone->order_type : '') == 'subscription' ? 'selected' : '' }}>Subscription</option>
                                <option value="custom" {{ old('order_type', isset($clone) ? $clone->order_type : '') == 'custom' ? 'selected' : '' }}>Custom</option>
                            </select>
                            <x-input-error :messages="$errors->get('order_type')" class="mt-2" />
                        </div>
                        <div class="md:col-span-2" id="expiry_date_field" style="display:none;">
                            <x-input-label for="expiry_date" :value="__('Expiry Date')" />
                            <x-text-input id="expiry_date" class="block mt-1 w-full" type="date" name="expiry_date" :value="old('expiry_date', isset($clone) && $clone->expiry_date ? $clone->expiry_date->format('Y-m-d') : date('Y-m-d', strtotime('+1 day')))" />
                            <div class="flex gap-2 mt-2">
                                <button type="button" class="quick-expiry bg-gray-200 px-2 py-1 rounded" data-days="7">1 Week</button>
                                <button type="button" class="quick-expiry bg-gray-200 px-2 py-1 rounded" data-days="15">15 Days</button>
                                <button type="button" class="quick-expiry bg-gray-200 px-2 py-1 rounded" data-days="30">1 Month</button>
                            </div>
                            <x-input-error :messages="$errors->get('expiry_date')" class="mt-2" />
                        </div>
                    @endif
                    <!-- Tag Selector -->
                    <div class="md:col-span-2">
                        <x-input-label for="tags" :value="__('Tags')" />
                        <div x-data="tagSelector({
                            allTags: @json($tags),
                            selected: [],
                            search: '',
                        })" class="mt-1 relative">
                            <div class="flex flex-wrap gap-2 mb-2">
                                <template x-for="tag in selectedTags" :key="tag.id">
                                    <span class="inline-flex items-center px-2 py-1 rounded bg-blue-100 text-blue-800 text-xs font-medium" :style="tag.color ? 'background:' + tag.color + '20; color:' + tag.color : ''">
                                        <span class="w-2 h-2 rounded-full mr-1 inline-block align-middle" :style="'background:' + tag.color"></span>
                                        <span x-text="tag.icon + ' ' + tag.name"></span>
                                        <button type="button" class="ml-1 text-blue-500 hover:text-blue-700" @click="removeTag(tag)">&times;</button>
                                        <input type="hidden" name="tags[]" :value="tag.id">
                                    </span>
                                </template>
                            </div>
                            <input type="text" x-model="search" placeholder="Search tags..." class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" @focus="showDropdown = true" @keydown.arrow-down.prevent="moveHighlight(1)" @keydown.arrow-up.prevent="moveHighlight(-1)" @keydown.enter.prevent="selectHighlighted()" @keydown.esc="showDropdown = false" @blur="setTimeout(() => showDropdown = false, 100)">
                            <div x-show="showDropdown && groupedFilteredTags.length > 0" class="absolute z-10 bg-white border border-gray-200 rounded-md mt-1 w-full shadow-lg max-h-48 overflow-auto">
                                <template x-for="(tags, category) in groupedFilteredTags" :key="category">
                                    <div>
                                        <div class="px-3 py-1 text-xs font-semibold text-gray-500 bg-gray-50" x-text="category"></div>
                                        <template x-for="(tag, idx) in tags" :key="tag.id">
                                            <div class="px-3 py-2 cursor-pointer flex items-center gap-2 hover:bg-blue-50"
                                                :class="{ 'bg-blue-100': isHighlighted(category, idx) }"
                                                @mousedown.prevent="addTag(tag)"
                                                @mouseenter="highlighted = { category, idx }">
                                                <span class="w-2 h-2 rounded-full inline-block" :style="'background:' + tag.color"></span>
                                                <span x-text="tag.icon"></span>
                                                <span x-text="tag.name"></span>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('tags')" class="mt-2" />
                    </div>
                    <!-- Photos -->
                    <div class="md:col-span-2">
                        <x-input-label for="photos" :value="__('Photos')" />
                        <input type="file" id="photos" name="photos[]" multiple accept="image/*" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                        <p class="text-sm text-gray-500 mt-1">You can select multiple photos. Maximum 5MB each.</p>
                        <x-input-error :messages="$errors->get('photos.*')" class="mt-2" />
                    </div>
                </div>
                <div class="flex items-center justify-end mt-4">
                    <a href="{{ route('provider.food-items.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
                        Cancel
                    </a>
                    <x-primary-button>
                        {{ __('Create Food Item') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<script>
// Make tagSelector globally available for Alpine
window.tagSelector = function({ allTags, selected = [], search = '' }) {
    return {
        allTags,
        selectedTags: selected,
        search,
        showDropdown: false,
        highlighted: { category: null, idx: 0 },
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
            this.search = '';
            this.showDropdown = false;
        },
        removeTag(tag) {
            this.selectedTags = this.selectedTags.filter(t => t.id !== tag.id);
        },
        moveHighlight(dir) {
            const flat = [];
            Object.entries(this.groupedFilteredTags).forEach(([category, tags]) => {
                tags.forEach((tag, idx) => flat.push({ category, idx, tag }));
            });
            if (!flat.length) return;
            let current = flat.findIndex(f => this.highlighted.category === f.category && this.highlighted.idx === f.idx);
            let next = current + dir;
            if (next < 0) next = flat.length - 1;
            if (next >= flat.length) next = 0;
            this.highlighted = { category: flat[next].category, idx: flat[next].idx };
        },
        selectHighlighted() {
            const group = this.groupedFilteredTags[this.highlighted.category];
            if (group && group[this.highlighted.idx]) {
                this.addTag(group[this.highlighted.idx]);
            }
        },
        isHighlighted(category, idx) {
            return this.highlighted.category === category && this.highlighted.idx === idx;
        },
    };
}

document.addEventListener('DOMContentLoaded', function() {
    function toggleExpiryField() {
        const orderType = document.getElementById('order_type').value;
        document.getElementById('expiry_date_field').style.display = (orderType === 'subscription' || orderType === 'custom') ? '' : 'none';
    }
    document.getElementById('order_type').addEventListener('change', toggleExpiryField);
    toggleExpiryField();
    document.querySelectorAll('.quick-expiry').forEach(btn => {
        btn.addEventListener('click', function() {
            const days = parseInt(this.getAttribute('data-days'));
            const today = new Date();
            today.setDate(today.getDate() + days);
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            document.getElementById('expiry_date').value = `${yyyy}-${mm}-${dd}`;
        });
    });
});
</script> 