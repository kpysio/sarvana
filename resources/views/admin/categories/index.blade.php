@extends('layouts.admin')

@section('title', 'Category Management')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Category Management</h2>
            <p class="text-gray-600">Manage food item categories</p>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="bg-white p-6 rounded-lg shadow mb-6">
    <form method="GET" class="flex space-x-4">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" 
                   class="w-full border border-gray-300 rounded-md px-3 py-2" 
                   placeholder="Search categories...">
        </div>
        <button type="submit" class="px-6 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700">
            Search
        </button>
    </form>
</div>

<!-- Bulk Actions -->
<div class="bg-white p-4 rounded-lg shadow mb-6">
    <form method="POST" action="{{ route('admin.categories.bulk-action') }}" id="bulk-form">
        @csrf
        <div class="flex items-center space-x-4">
            <select name="action" class="border border-gray-300 rounded-md px-3 py-2">
                <option value="">Bulk Actions</option>
                <option value="delete">Delete Categories</option>
                <option value="merge">Merge Categories</option>
            </select>
            <input type="text" name="target_category" placeholder="Target category name" 
                   class="border border-gray-300 rounded-md px-3 py-2 hidden" id="target-category">
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700" 
                    onclick="return confirm('Are you sure you want to perform this action?')">
                Apply
            </button>
        </div>
    </form>
</div>

<!-- Categories Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Categories ({{ $categories->count() }})</h3>
            <div class="flex items-center space-x-2">
                <input type="checkbox" id="select-all" class="rounded border-gray-300">
                <label for="select-all" class="text-sm text-gray-600">Select All</label>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" class="bulk-select rounded border-gray-300">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Food Items</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($categories as $category)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" name="categories[]" value="{{ $category->category }}" 
                               class="bulk-select rounded border-gray-300">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-medium text-gray-900">{{ $category->category }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $category->item_count }} items
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.categories.show', $category->category) }}" 
                               class="text-blue-600 hover:text-blue-900">View Items</a>
                            <button onclick="editCategory('{{ $category->category }}')" 
                                    class="text-green-600 hover:text-green-900">Rename</button>
                            @if($category->item_count == 0)
                            <form method="POST" action="{{ route('admin.categories.destroy', $category->category) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Are you sure you want to delete this category?')">
                                    Delete
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="edit-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Rename Category</h3>
            </div>
            <form method="POST" id="edit-form" class="p-6">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="new_category" class="block text-sm font-medium text-gray-700 mb-2">New Category Name</label>
                    <input type="text" id="new_category" name="new_category" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select-all');
    const bulkSelects = document.querySelectorAll('.bulk-select');
    const actionSelect = document.querySelector('select[name="action"]');
    const targetCategory = document.getElementById('target-category');
    
    selectAll.addEventListener('change', function() {
        bulkSelects.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    
    bulkSelects.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(bulkSelects).every(cb => cb.checked);
            selectAll.checked = allChecked;
        });
    });
    
    actionSelect.addEventListener('change', function() {
        if (this.value === 'merge') {
            targetCategory.classList.remove('hidden');
        } else {
            targetCategory.classList.add('hidden');
        }
    });
});

function editCategory(categoryName) {
    const modal = document.getElementById('edit-modal');
    const form = document.getElementById('edit-form');
    const input = document.getElementById('new_category');
    
    form.action = `/admin/categories/${encodeURIComponent(categoryName)}`;
    input.value = categoryName;
    modal.classList.remove('hidden');
}

function closeEditModal() {
    const modal = document.getElementById('edit-modal');
    modal.classList.add('hidden');
}
</script>
@endsection 