@extends('layouts.admin')

@section('title', 'Tag Details - ' . $tag->name)

@section('content')
<div class="mb-6">

    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Tag Details</h2>
            <p class="text-gray-600">{{ $tag->name }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.tags.edit', $tag) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Edit Tag
            </a>
            <a href="{{ route('admin.tags.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                Back to Tags
            </a>
        </div>
    </div>
</div>

<!-- Tag Information -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Tag Details -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Tag Information</h3>
        <div class="space-y-3">
            <div>
                <label class="text-sm font-medium text-gray-500">Name</label>
                <div class="flex items-center space-x-2 mt-1">
                    @if($tag->color)
                    <div class="w-4 h-4 rounded-full" style="background-color: {{ $tag->color }};"></div>
                    @endif
                    <p class="text-gray-900 font-medium">{{ $tag->name }}</p>
                </div>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Category</label>
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                    {{ ucfirst(str_replace('_', ' ', $tag->category)) }}
                </span>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Description</label>
                <p class="text-gray-900">{{ $tag->description ?? 'No description provided' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Color</label>
                <p class="text-gray-900">{{ $tag->color ?? 'No color set' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Created</label>
                <p class="text-gray-900">{{ $tag->created_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Usage Statistics -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Usage Statistics</h3>
        <div class="space-y-3">
            <div>
                <label class="text-sm font-medium text-gray-500">Food Items</label>
                <p class="text-2xl font-bold text-gray-900">{{ $tag->food_items_count }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Usage Rate</label>
                <div class="flex items-center space-x-2">
                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                        @php
                            $totalItems = \App\Models\FoodItem::count();
                            $usageRate = $totalItems > 0 ? ($tag->food_items_count / $totalItems) * 100 : 0;
                        @endphp
                        <div class="bg-orange-600 h-2 rounded-full" style="width: {{ $usageRate }}%"></div>
                    </div>
                    <span class="text-sm text-gray-600">{{ number_format($usageRate, 1) }}%</span>
                </div>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Category Distribution</label>
                <p class="text-sm text-gray-600">Tags in this category: {{ \App\Models\Tag::where('category', $tag->category)->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="space-y-3">
            <a href="{{ route('admin.tags.edit', $tag) }}" 
               class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-center block">
                Edit Tag
            </a>
            @if($tag->food_items_count == 0)
            <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700"
                        onclick="return confirm('Are you sure you want to delete this tag?')">
                    Delete Tag
                </button>
            </form>
            @else
            <button disabled class="w-full bg-gray-400 text-white px-4 py-2 rounded-md cursor-not-allowed">
                Cannot Delete (In Use)
            </button>
            @endif
        </div>
    </div>
</div>

<!-- Category Information -->
<div class="bg-white p-6 rounded-lg shadow mb-8">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Category Information</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="text-sm font-medium text-gray-500">Category Name</label>
            <p class="text-gray-900 font-medium">{{ ucfirst(str_replace('_', ' ', $tag->category)) }}</p>
        </div>
        <div>
            <label class="text-sm font-medium text-gray-500">Category Type</label>
            <p class="text-gray-900">{{ $tag->category }}</p>
        </div>
        <div>
            <label class="text-sm font-medium text-gray-500">Tags in Same Category</label>
            <p class="text-gray-900">{{ \App\Models\Tag::where('category', $tag->category)->count() }} tags</p>
        </div>
        <div>
            <label class="text-sm font-medium text-gray-500">Category Description</label>
            <p class="text-gray-900">
                @switch($tag->category)
                    @case('food_type')
                        Tags that describe the type of food (e.g., Vegetarian, Non-vegetarian, Vegan)
                        @break
                    @case('festival')
                        Tags related to festivals and celebrations (e.g., Diwali, Christmas, Eid)
                        @break
                    @case('seasonal')
                        Tags related to seasons and weather (e.g., Summer, Winter, Monsoon)
                        @break
                    @case('food_origin')
                        Tags indicating the origin or region of food (e.g., North Indian, South Indian, Chinese)
                        @break
                    @case('dietary')
                        Tags for dietary restrictions and preferences (e.g., Gluten-free, Sugar-free, Low-carb)
                        @break
                    @case('cuisine')
                        Tags for different cuisines (e.g., Italian, Mexican, Thai)
                        @break
                    @default
                        Custom category
                @endswitch
            </p>
        </div>
    </div>
</div>

<!-- Food Items Using This Tag -->
<div class="bg-white p-6 rounded-lg shadow">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Food Items Using This Tag</h3>
    
    @if($tag->foodItems->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Food Item</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provider</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($tag->foodItems as $foodItem)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $foodItem->title }}</div>
                            <div class="text-sm text-gray-500">{{ $foodItem->category }}</div>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $foodItem->created_at->format('M d, Y') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-8">
        <div class="text-gray-500">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No food items</h3>
            <p class="mt-1 text-sm text-gray-500">This tag is not currently used by any food items.</p>
        </div>
    </div>
    @endif
</div>
@endsection 