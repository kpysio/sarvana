@extends('layouts.admin')

@section('title', 'Tag Management')

@section('content')
<div class="flex items-center justify-between mb-8">
    <h2 class="text-2xl font-bold text-gray-800">Tag Management</h2>
    <a href="{{ route('admin.tags.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Add New Tag
            </a>
</div>

@php
$categoryOptions = [
    'food_type' => 'Food Type',
    'festival' => 'Festival',
    'seasonal' => 'Seasonal',
    'food_origin' => 'Food Origin',
    'dietary' => 'Dietary',
    'cuisine' => 'Cuisine',
];
@endphp
<div class="bg-white p-6 rounded-lg shadow mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <input id="search-input" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="Search tags...">
        </div>
        <div>
            <select id="category-select" class="w-full border border-gray-300 rounded-md px-3 py-2">
                <option value="">All Categories</option>
                @foreach($categoryOptions as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div></div>
    </div>
</div>

<!-- Bulk Actions (still server-side for now) -->
<div class="bg-white p-4 rounded-lg shadow mb-6">
    <form method="POST" action="{{ route('admin.tags.bulk-action') }}" id="bulk-form">
        @csrf
        <div class="flex items-center space-x-4">
            <select name="action" id="bulk-action-select" class="border border-gray-300 rounded-md px-3 py-2">
                <option value="">Bulk Actions</option>
                <option value="delete">Delete Tags</option>
            </select>
            <button type="submit" id="bulk-apply-btn" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed" 
                    disabled
                    onclick="return confirm('Are you sure you want to delete the selected tags?')">
                Apply
            </button>
        </div>
    </form>
</div>

<!-- Tags Table (Rendered by JS) -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Tags (<span id="tag-count"></span>)</h3>
            <div class="flex items-center space-x-2">
                <input type="checkbox" id="select-all" class="rounded border-gray-300">
                <label for="select-all" class="text-sm text-gray-600">Select All</label>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" id="tags-table">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" class="bulk-select rounded border-gray-300" id="header-bulk-select">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tag</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Food Items</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody id="tags-tbody" class="bg-white divide-y divide-gray-200">
                <!-- JS will render rows here -->
            </tbody>
        </table>
    </div>
</div>

<script>
const allTags = @json($allTags);
const categoryOptions = @json($categoryOptions);

function renderTags(tags) {
    const tbody = document.getElementById('tags-tbody');
    const tagCount = document.getElementById('tag-count');
    tbody.innerHTML = '';
    tagCount.textContent = tags.length;
    if (tags.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-gray-500 py-8">No tags found for the selected filter.</td></tr>`;
        return;
    }
    tags.forEach(tag => {
        tbody.innerHTML += `
        <tr>
            <td class="px-6 py-4 whitespace-nowrap">
                <input type="checkbox" name="tags[]" value="${tag.id}" class="bulk-select rounded border-gray-300">
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    ${tag.color ? `<div class='w-4 h-4 rounded-full mr-3' style='background-color: ${tag.color};'></div>` : ''}
                    <span class="text-sm font-medium text-gray-900">${tag.name}</span>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                    ${categoryOptions[tag.category] || tag.category.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}
                </span>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm text-gray-900 max-w-xs truncate">
                    ${tag.description || 'No description'}
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                    ${tag.food_items_count} items
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${new Date(tag.created_at).toLocaleDateString('en-GB', { year: 'numeric', month: 'short', day: '2-digit' })}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex items-center space-x-2">
                    <a href="/admin/tags/${tag.id}" class="text-blue-600 hover:text-blue-900">View</a>
                    <a href="/admin/tags/${tag.id}/edit" class="text-green-600 hover:text-green-900">Edit</a>
                    <form method="POST" action="/admin/tags/${tag.id}" class="inline" onsubmit="return confirm('Are you sure you want to delete this tag?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                    </form>
                </div>
            </td>
        </tr>
        `;
    });
}

function filterTags() {
    const search = document.getElementById('search-input').value.trim().toLowerCase();
    const category = document.getElementById('category-select').value;
    let filtered = allTags;
    if (category) {
        filtered = filtered.filter(tag => tag.category === category);
    }
    if (search) {
        filtered = filtered.filter(tag => tag.name.toLowerCase().includes(search));
    }
    renderTags(filtered);
}

document.addEventListener('DOMContentLoaded', function() {
    // Initial render
    renderTags(allTags);
    // Filter on category change
    document.getElementById('category-select').addEventListener('change', filterTags);
    // Filter on search (keyup and Enter)
    const searchInput = document.getElementById('search-input');
    let debounceTimeout;
    searchInput.addEventListener('keyup', function(e) {
        clearTimeout(debounceTimeout);
        if (e.key === 'Enter') {
            filterTags();
        } else {
            debounceTimeout = setTimeout(filterTags, 200);
        }
    });
    // Select all logic
    const selectAll = document.getElementById('select-all');
    selectAll.addEventListener('change', function() {
        document.querySelectorAll('.bulk-select').forEach(cb => { cb.checked = selectAll.checked; });
    });
});
</script>
@endsection 