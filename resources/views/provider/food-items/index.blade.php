@extends('layouts.provider')

@section('content')
<div>
    <!-- Page Title & Description -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">My Store</h1>
            <p class="text-gray-600 dark:text-gray-400">View and manage all your food items</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('provider.food-items.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add New Item</a>
        </div>
    </div>
    <!-- Filter/Search Bar -->
    <div class="bg-white p-4 rounded shadow mb-6">
        <form method="GET" class="flex flex-col md:flex-row md:items-end gap-4 md:gap-2">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search food items..." class="w-full border border-gray-300 rounded-md px-3 py-2">
            </div>
            <div class="w-full md:w-48">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    <option value="">All Status</option>
                    <option value="active" @if(request('status')=='active') selected @endif>Active</option>
                    <option value="inactive" @if(request('status')=='inactive') selected @endif>Inactive</option>
                </select>
            </div>
            <div class="w-full md:w-32 flex items-end">
                <button type="submit" class="w-full bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700">Filter</button>
                @if(request('search') || request('status'))
                    <a href="{{ route('provider.food-items.index') }}" class="ml-2 w-full bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 text-center">Reset</a>
                @endif
            </div>
        </form>
    </div>
    <!-- Food Items Table -->
    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2">Image</th>
                    <th class="px-4 py-2">Title</th>
                    <th class="px-4 py-2">Category</th>
                    <th class="px-4 py-2">Price</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Qty</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items ?? [] as $item)
                <tr>
                    <td class="border px-4 py-2">
                        <img src="{{ $item->image_url ?? 'https://source.unsplash.com/80x60/?food,' . $item->id }}" alt="Food Item" class="rounded h-12 w-16 object-cover">
                    </td>
                    <td class="border px-4 py-2 font-semibold">{{ $item->title }}</td>
                    <td class="border px-4 py-2 text-sm text-gray-600">{{ $item->category ?? 'Snacks' }}</td>
                    <td class="border px-4 py-2 font-semibold">£{{ $item->price ?? '0.00' }}</td>
                    <td class="border px-4 py-2">
                        <span class="inline-block px-2 py-1 rounded text-xs font-medium {{ $item->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($item->status) }}</span>
                    </td>
                    <td class="border px-4 py-2 text-xs text-gray-500">{{ $item->available_quantity ?? 0 }}</td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('provider.food-items.show', $item->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-semibold hover:bg-blue-700 transition">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-gray-500">No food items found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection 