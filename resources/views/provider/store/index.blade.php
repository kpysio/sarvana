@extends('layouts.provider')

@section('content')
<div class="max-w-7xl mx-auto mt-8">
    <!-- Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-4 flex items-center">
            <div class="bg-purple-100 text-purple-600 rounded-full p-3 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
            </div>
            <div>
                <div class="text-lg font-bold">{{ $totalItems ?? '0' }}</div>
                <div class="text-gray-500 text-sm">Total Items</div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 flex items-center">
            <div class="bg-green-100 text-green-600 rounded-full p-3 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            </div>
            <div>
                <div class="text-lg font-bold">{{ $activeItems ?? '0' }}</div>
                <div class="text-gray-500 text-sm">Active</div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 flex items-center">
            <div class="bg-red-100 text-red-600 rounded-full p-3 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </div>
            <div>
                <div class="text-lg font-bold">{{ $inactiveItems ?? '0' }}</div>
                <div class="text-gray-500 text-sm">Inactive</div>
            </div>
        </div>
    </div>
    <!-- Top Bar: Create Button & Filter -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <a href="{{ route('provider.food-items.create') }}" class="inline-flex items-center bg-purple-600 text-white px-5 py-2 rounded-lg font-semibold shadow hover:bg-purple-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Create New Food Item
        </a>
        <form class="flex gap-2 items-center w-full md:w-auto" method="GET" action="{{ route('provider.food-items.index') }}">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search food items..." class="border rounded px-3 py-2 w-full md:w-64 focus:ring-2 focus:ring-purple-400">
            <select name="status" class="border rounded px-3 py-2">
                <option value="">All Status</option>
                <option value="active" @if(request('status')=='active') selected @endif>Active</option>
                <option value="inactive" @if(request('status')=='inactive') selected @endif>Inactive</option>
            </select>
            <button class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300 font-medium">Filter</button>
        </form>
    </div>
    <!-- Food Items Grid/List (static for now, replace with @foreach for real data) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($items ?? [] as $item)
        <div class="bg-white rounded-xl shadow p-4 flex flex-col hover:shadow-lg transition">
            <img src="{{ $item->image_url ?? 'https://source.unsplash.com/400x250/?food,' . $item->id }}" alt="Food Item" class="rounded-lg mb-4 h-40 object-cover">
            <div class="flex-1">
                <div class="font-bold text-lg mb-1">{{ $item->title }}</div>
                <div class="text-gray-500 text-sm mb-2">Category: {{ $item->category ?? 'Snacks' }}</div>
                <div class="text-gray-700 font-semibold mb-2">£{{ $item->price ?? '0.00' }}</div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-block px-2 py-1 rounded text-xs font-medium {{ $item->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($item->status) }}</span>
                    <span class="text-xs text-gray-400">Qty: {{ $item->available_quantity ?? 0 }}</span>
                </div>
            </div>
            <div class="flex gap-2 mt-4">
                <a href="#" class="flex-1 bg-blue-100 text-blue-700 px-3 py-2 rounded text-center text-sm font-semibold hover:bg-blue-200">Edit</a>
                <a href="#" class="flex-1 bg-red-100 text-red-700 px-3 py-2 rounded text-center text-sm font-semibold hover:bg-red-200">Delete</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection 