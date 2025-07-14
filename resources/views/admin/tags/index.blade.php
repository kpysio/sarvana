@extends('layouts.admin')

@section('title', 'Tag Management')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Tag Management</h2>
            <p class="text-gray-600 dark:text-gray-400">View and manage all Tags and categories</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.tags.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Add New Tag
            </a>
        </div>
    </div>
</div>
<!-- Filters -->
<div class="bg-white p-6 rounded-lg shadow mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="w-full border border-gray-300 rounded-md px-3 py-2"
                   placeholder="Tag name or description...">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
            <select name="category" class="w-full border border-gray-300 rounded-md px-3 py-2">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" @if(request('category') == $cat) selected @endif>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div></div>
        <div class="flex items-end">
            <button type="submit" class="w-full bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700">
                Filter
            </button>
            @if(request('search') || request('category'))
                <a href="{{ route('admin.tags.index') }}" class="ml-2 w-full bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 text-center">
                    Reset
                </a>
            @endif
        </div>
    </form>
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

<!-- Tags Table (Server Rendered) -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Tags ({{ $tags->total() }})</h3>
            <div class="flex items-center space-x-2">
                <input type="checkbox" id="select-all" class="rounded border-gray-300">
                <label for="select-all" class="text-sm text-gray-600 dark:text-gray-300">Select All</label>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        <input type="checkbox" class="bulk-select rounded border-gray-300" id="header-bulk-select">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tag</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Food Items</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($tags as $tag)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="bulk-select rounded border-gray-300">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($tag->color)
                                <div class='w-4 h-4 rounded-full mr-3' style='background-color: {{ $tag->color }};'></div>
                            @endif
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $tag->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            {{ $tag->category }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 dark:text-gray-100 max-w-xs truncate">
                            {{ $tag->description ?? 'No description' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            {{ $tag->food_items_count ?? 0 }} items
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                        {{ $tag->created_at ? $tag->created_at->format('d M Y') : '' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.tags.show', $tag->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200">View</a>
                            <a href="{{ route('admin.tags.edit', $tag->id) }}" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-200">Edit</a>
                            <form method="POST" action="{{ route('admin.tags.destroy', $tag->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this tag?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-gray-500 dark:text-gray-300 py-8">No tags found for the selected filter.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
        {{ $tags->links() }}
    </div>
</div>
@endsection 