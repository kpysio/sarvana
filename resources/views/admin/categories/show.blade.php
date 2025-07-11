@extends('layouts.admin')

@section('title', 'Category Details - ' . $category)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Category Details</h2>
            <p class="text-gray-600">{{ $category }}</p>
        </div>
        <div class="flex space-x-2">
            <button onclick="editCategory('{{ $category }}')" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Rename Category
            </button>
            <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                Back to Categories
            </a>
        </div>
    </div>
</div>

<!-- Category Information -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Category Details -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Category Information</h3>
        <div class="space-y-3">
            <div>
                <label class="text-sm font-medium text-gray-500">Name</label>
                <p class="text-gray-900 font-medium">{{ $category }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Food Items</label>
                <p class="text-2xl font-bold text-gray-900">{{ $foodItems->total() }}</p>
            </div>
        </div>
    </div>

    <!-- Usage Statistics -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Usage Statistics</h3>
        <div class="space-y-3">
            @php
                $totalItems = \App\Models\FoodItem::count();
                $usageRate = $totalItems > 0 ? ($foodItems->total() / $totalItems) * 100 : 0;
                $activeItems = $foodItems->where('status', 'active')->count();
                $inactiveItems = $foodItems->where('status', 'inactive')->count();
            @endphp
            <div>
                <label class="text-sm font-medium text-gray-500">Usage Rate</label>
                <div class="flex items-center space-x-2">
                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                        <div class="bg-orange-600 h-2 rounded-full" style="width: {{ $usageRate }}%"></div>
                    </div>
                    <span class="text-sm text-gray-600">{{ number_format($usageRate, 1) }}%</span>
                </div>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Active Items</label>
                <p class="text-gray-900">{{ $activeItems }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Inactive Items</label>
                <p class="text-gray-900">{{ $inactiveItems }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="space-y-3">
            <button onclick="editCategory('{{ $category }}')" 
                    class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                Rename Category
            </button>
            @if($foodItems->total() == 0)
            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700"
                        onclick="return confirm('Are you sure you want to delete this category?')">
                    Delete Category
                </button>
            </form>
            @else
            <button disabled class="w-full bg-gray-400 text-white px-4 py-2 rounded-md cursor-not-allowed">
                Cannot Delete (Has Items)
            </button>
            @endif
        </div>
    </div>
</div>

<!-- Food Items in This Category -->
<div class="bg-white p-6 rounded-lg shadow">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Food Items in This Category</h3>
    
    @if($foodItems->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Food Item</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provider</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tags</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($foodItems as $foodItem)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $foodItem->title }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($foodItem->description, 50) }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $foodItem->provider->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-medium text-gray-900">₹{{ number_format($foodItem->price, 2) }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                            @if($foodItem->status === 'active') bg-green-100 text-green-800
                            @elseif($foodItem->status === 'inactive') bg-gray-100 text-gray-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($foodItem->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex flex-wrap gap-1">
                            @foreach($foodItem->tags as $tag)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                @if($tag->color)
                                <div class="w-2 h-2 rounded-full mr-1" style="background-color: {{ $tag->color }};"></div>
                                @endif
                                {{ $tag->name }}
                            </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $foodItem->created_at->format('M d, Y') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="mt-6">
        {{ $foodItems->links() }}
    </div>
    @else
    <div class="text-center py-8">
        <div class="text-gray-500">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No food items</h3>
            <p class="mt-1 text-sm text-gray-500">This category doesn't have any food items yet.</p>
        </div>
    </div>
    @endif
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