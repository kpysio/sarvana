@extends('layouts.admin')

@section('title', 'Food Items')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Food Item Management</h1>
    <form method="GET" class="flex gap-2">
        <select name="category" class="border rounded px-2 py-1">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->name }}" {{ request('category') == $cat->name ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="status" class="border rounded px-2 py-1">
            <option value="">All Status</option>
            <option value="live" {{ request('status') == 'live' ? 'selected' : '' }}>Live</option>
        </select>
        <select name="due" class="border rounded px-2 py-1">
            <option value="">Any Due Date</option>
            <option value="today" {{ request('due') == 'today' ? 'selected' : '' }}>Due Today</option>
        </select>
        <label class="flex items-center gap-1">
            <input type="checkbox" name="half_full" value="1" {{ request('half_full') ? 'checked' : '' }}>
            50%+ Orders
        </label>
        <button class="bg-blue-600 text-white px-4 py-1 rounded">Filter</button>
    </form>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded shadow p-4">
        <h2 class="font-semibold mb-2">Popular Food Items</h2>
        <ul>
            @foreach($popularItems as $item)
                <li>{{ $item->title }} ({{ $item->orders_count }} orders)</li>
            @endforeach
        </ul>
    </div>
    <div class="bg-white rounded shadow p-4">
        <h2 class="font-semibold mb-2">Popular Categories</h2>
        <ul>
            @foreach($popularCategories as $cat)
                <li>{{ $cat->category }} ({{ $cat->orders_count }} orders)</li>
            @endforeach
        </ul>
    </div>
</div>
<div class="bg-white rounded shadow overflow-x-auto">
    <table class="min-w-full">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2">Title</th>
                <th class="px-4 py-2">Provider</th>
                <th class="px-4 py-2">Available Date</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Orders</th>
                <th class="px-4 py-2">% Filled</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($foodItems as $item)
                <tr>
                    <td class="border px-4 py-2">{{ $item->title }}</td>
                    <td class="border px-4 py-2">{{ $item->provider->name ?? '-' }}</td>
                    <td class="border px-4 py-2">{{ $item->available_date }}</td>
                    <td class="border px-4 py-2">{{ ucfirst($item->status) }}</td>
                    <td class="border px-4 py-2">{{ $item->orders->count() }}</td>
                    <td class="border px-4 py-2">
                        @php
                            $percent = $item->available_quantity > 0 ? round(($item->orders->count() / $item->available_quantity) * 100) : 0;
                        @endphp
                        {{ $percent }}%
                    </td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('admin.food-items.show', $item) }}" class="text-blue-600 hover:underline">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center py-4">No food items found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $foodItems->links() }}</div>
</div>
@endsection 