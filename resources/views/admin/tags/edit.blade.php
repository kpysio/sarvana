@extends('layouts.admin')

@section('title', 'Edit Tag - ' . $tag->name)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Tag</h2>
            <p class="text-gray-600">{{ $tag->name }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.tags.show', $tag) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                View Tag
            </a>
            <a href="{{ route('admin.tags.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                Back to Tags
            </a>
        </div>
    </div>
</div>

<div class="bg-white p-6 rounded-lg shadow">
    <form method="POST" action="{{ route('admin.tags.update', $tag) }}">
        @csrf
        @method('PATCH')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Tag Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $tag->name) }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500"
                           placeholder="e.g., Vegetarian, Spicy, Gluten-Free">
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select id="category" name="category" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="">Select a category</option>
                        <option value="food_type" {{ old('category', $tag->category) == 'food_type' ? 'selected' : '' }}>Food Type</option>
                        <option value="festival" {{ old('category', $tag->category) == 'festival' ? 'selected' : '' }}>Festival</option>
                        <option value="seasonal" {{ old('category', $tag->category) == 'seasonal' ? 'selected' : '' }}>Seasonal</option>
                        <option value="food_origin" {{ old('category', $tag->category) == 'food_origin' ? 'selected' : '' }}>Food Origin</option>
                        <option value="dietary" {{ old('category', $tag->category) == 'dietary' ? 'selected' : '' }}>Dietary</option>
                        <option value="cuisine" {{ old('category', $tag->category) == 'cuisine' ? 'selected' : '' }}>Cuisine</option>
                        <option value="custom" {{ old('category', $tag->category) == 'custom' ? 'selected' : '' }}>Custom Category</option>
                    </select>
                    @error('category')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div id="custom-category" class="hidden">
                    <label for="custom_category" class="block text-sm font-medium text-gray-700 mb-2">Custom Category Name</label>
                    <input type="text" id="custom_category" name="custom_category" value="{{ old('custom_category') }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500"
                           placeholder="Enter custom category name">
                </div>
                
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="4" 
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500"
                              placeholder="Describe what this tag represents...">{{ old('description', $tag->description) }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 mb-2">Color (Optional)</label>
                    <input type="color" id="color" name="color" value="{{ old('color', $tag->color ?? '#3B82F6') }}" 
                           class="w-full h-12 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <p class="text-sm text-gray-500 mt-1">Choose a color to represent this tag</p>
                    @error('color')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Preview</h4>
                    <div class="flex items-center space-x-2">
                        <div id="color-preview" class="w-4 h-4 rounded-full" style="background-color: {{ $tag->color ?? '#3B82F6' }};"></div>
                        <span id="name-preview" class="text-sm font-medium text-gray-900">{{ $tag->name }}</span>
                        <span id="category-preview" class="text-xs text-gray-500"></span>
                    </div>
                </div>
                
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-blue-700 mb-2">Usage Statistics</h4>
                    <p class="text-sm text-blue-600">This tag is used by {{ $tag->food_items_count }} food items</p>
                </div>
                
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-yellow-700 mb-2">Category Guide</h4>
                    <div class="text-xs text-yellow-600 space-y-1">
                        <p><strong>Food Type:</strong> Vegetarian, Non-vegetarian, Vegan, etc.</p>
                        <p><strong>Festival:</strong> Diwali, Christmas, Eid, etc.</p>
                        <p><strong>Seasonal:</strong> Summer, Winter, Monsoon, etc.</p>
                        <p><strong>Food Origin:</strong> North Indian, South Indian, Chinese, etc.</p>
                        <p><strong>Dietary:</strong> Gluten-free, Sugar-free, Low-carb, etc.</p>
                        <p><strong>Cuisine:</strong> Italian, Mexican, Thai, etc.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('admin.tags.index') }}" 
               class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700">
                Update Tag
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const categorySelect = document.getElementById('category');
    const customCategoryDiv = document.getElementById('custom-category');
    const customCategoryInput = document.getElementById('custom_category');
    const colorInput = document.getElementById('color');
    const namePreview = document.getElementById('name-preview');
    const categoryPreview = document.getElementById('category-preview');
    const colorPreview = document.getElementById('color-preview');
    
    nameInput.addEventListener('input', function() {
        namePreview.textContent = this.value || 'Tag Name';
    });
    
    categorySelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            customCategoryDiv.classList.remove('hidden');
            customCategoryInput.required = true;
        } else {
            customCategoryDiv.classList.add('hidden');
            customCategoryInput.required = false;
            customCategoryInput.value = '';
        }
        updateCategoryPreview();
    });
    
    customCategoryInput.addEventListener('input', function() {
        updateCategoryPreview();
    });
    
    colorInput.addEventListener('input', function() {
        colorPreview.style.backgroundColor = this.value;
    });
    
    function updateCategoryPreview() {
        let categoryText = '';
        if (categorySelect.value === 'custom') {
            categoryText = customCategoryInput.value;
        } else if (categorySelect.value) {
            categoryText = categorySelect.options[categorySelect.selectedIndex].text;
        }
        categoryPreview.textContent = categoryText ? `(${categoryText})` : '';
    }
    
    // Initialize preview
    updateCategoryPreview();
});
</script>
@endsection 