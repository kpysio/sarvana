@extends('layouts.provider')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-purple-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 mb-1">My Food Items</h1>
                <p class="text-gray-500 text-sm">Manage your menu, update availability, and keep your store fresh for customers.</p>
            </div>
            <a href="{{ route('provider.food-items.create') }}" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-base font-semibold shadow transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Add New Item
            </a>
        </div>
        <!-- Search & Filter Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 bg-white/80 rounded-xl shadow p-4 border border-gray-100">
            <form method="GET" class="flex flex-1 flex-col sm:flex-row gap-2 w-full">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search food items..." class="flex-1 border border-gray-200 rounded-full px-4 py-2 text-sm focus:ring focus:border-blue-300 shadow-sm bg-gray-50" />
                <select name="status" class="border border-gray-200 rounded-full px-3 py-2 text-sm shadow-sm bg-gray-50">
                    <option value="">All Statuses</option>
                    <option value="active" @if(request('status')==='active') selected @endif>Active</option>
                    <option value="inactive" @if(request('status')==='inactive') selected @endif>Inactive</option>
                    <option value="expired" @if(request('status')==='expired') selected @endif>Expired</option>
                </select>
                <button class="bg-blue-600 text-white px-5 py-2 rounded-full font-semibold shadow hover:bg-blue-700 text-sm">Filter</button>
            </form>
            <div class="flex gap-2 items-center">
                <form method="GET" class="hidden">
                    <input type="hidden" id="food-view" name="view" value="{{ request('view', 'grid') }}" />
                </form>
                <button onclick="document.getElementById('food-view').value='grid'; this.form && this.form.submit();" class="px-3 py-2 rounded-full text-xs font-semibold border transition {{ request('view', 'grid')==='grid' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'bg-white text-gray-600 border-gray-300' }}" title="Grid View">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><rect x="4" y="4" width="7" height="7" rx="2"/><rect x="13" y="4" width="7" height="7" rx="2"/><rect x="4" y="13" width="7" height="7" rx="2"/><rect x="13" y="13" width="7" height="7" rx="2"/></svg>
                </button>
                <button onclick="document.getElementById('food-view').value='list'; this.form && this.form.submit();" class="px-3 py-2 rounded-full text-xs font-semibold border transition {{ request('view', 'grid')==='list' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'bg-white text-gray-600 border-gray-300' }}" title="List View">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><rect x="4" y="6" width="16" height="4" rx="2"/><rect x="4" y="14" width="16" height="4" rx="2"/></svg>
                </button>
            </div>
        </div>
        @php
            $grouped = $foodItems->groupBy('status');
            $statuses = ['active' => 'Active Items', 'inactive' => 'Inactive Items', 'expired' => 'Expired Items'];
            $view = request('view', 'grid');
        @endphp
        @foreach($statuses as $status => $label)
            @if(isset($grouped[$status]) && $grouped[$status]->count())
                <h3 class="text-xl font-bold mt-10 mb-4 text-{{ $status === 'active' ? 'green' : ($status === 'inactive' ? 'gray' : 'red') }}-700">{{ $label }}</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($grouped[$status] as $item)
                        @php
                            $photos = $item->photos;
                            if (is_string($photos)) {
                                $photos = json_decode($photos, true);
                            }
                            if (!is_array($photos)) {
                                $photos = [];
                            }
                        @endphp
                        <div class="bg-white rounded-xl shadow p-5 flex flex-col gap-3 relative">
                            <div class="flex gap-3 items-center">
                                @if(count($photos) > 0)
                                    @php
                                        $photo = $photos[0];
                                        $isUrl = Str::startsWith($photo, ['http://', 'https://']);
                                        $src = $isUrl ? $photo : asset('storage/' . ltrim($photo, '/'));
                                    @endphp
                                    <img class="w-20 h-20 object-cover rounded-lg" src="{{ $src }}" alt="">
                                @else
                                    <div class="w-20 h-20 bg-gray-200 flex items-center justify-center rounded-lg text-gray-400">No Photo</div>
                                @endif
                                <div>
                                    <div class="font-semibold text-lg text-gray-900">{{ $item->title }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($item->description, 50) }}</div>
                                    <div class="flex flex-wrap gap-2 mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->status === 'active' ? 'bg-green-100 text-green-800' : ($item->status === 'inactive' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded-full text-xs bg-blue-50 text-blue-700">{{ ucfirst($item->category) }}</span>
                                        <span class="px-2 py-0.5 rounded-full text-xs bg-yellow-50 text-yellow-700">£{{ $item->price }}</span>
                                        <span class="px-2 py-0.5 rounded-full text-xs bg-purple-50 text-purple-700">Qty: {{ $item->available_quantity }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2 mt-3">
                                <a href="{{ route('provider.food-items.show', $item) }}" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 rounded-lg font-semibold">View</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endforeach
        @if($foodItems->isEmpty())
            <div class="text-center py-16">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-blue-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 10c-4.418 0-8-1.79-8-4V6a2 2 0 012-2h12a2 2 0 012 2v8c0 2.21-3.582 4-8 4z" /></svg>
                <p class="text-gray-500 mb-4 text-lg">You haven't added any food items yet.</p>
                <a href="{{ route('provider.food-items.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold shadow">Create Your First Item</a>
            </div>
        @endif
    </div>
</div>
@endsection 