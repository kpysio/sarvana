@extends('layouts.provider')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Search & Filter Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <form method="GET" class="flex flex-1 flex-col sm:flex-row gap-2 w-full">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search food items..." class="flex-1 border rounded-lg px-4 py-2 text-sm focus:ring focus:border-blue-300 shadow-sm" />
                <select name="status" class="border rounded-lg px-3 py-2 text-sm shadow-sm">
                    <option value="">All Statuses</option>
                    <option value="active" @if(request('status')==='active') selected @endif>Active</option>
                    <option value="inactive" @if(request('status')==='inactive') selected @endif>Inactive</option>
                    <option value="expired" @if(request('status')==='expired') selected @endif>Expired</option>
                </select>
                <button class="bg-blue-600 text-white px-5 py-2 rounded-lg font-semibold shadow hover:bg-blue-700 text-sm">Filter</button>
            </form>
            <div class="flex gap-2 justify-end">
                <button onclick="document.getElementById('food-view').value='grid'; this.form && this.form.submit();" class="px-4 py-2 rounded-lg text-xs font-semibold border {{ request('view', 'grid')==='grid' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'bg-white text-gray-600 border-gray-300' }}">Grid</button>
                <button onclick="document.getElementById('food-view').value='list'; this.form && this.form.submit();" class="px-4 py-2 rounded-lg text-xs font-semibold border {{ request('view', 'grid')==='list' ? 'bg-blue-100 text-blue-700 border-blue-300' : 'bg-white text-gray-600 border-gray-300' }}">List</button>
                <form method="GET" class="hidden">
                    <input type="hidden" id="food-view" name="view" value="{{ request('view', 'grid') }}" />
                </form>
                <a href="{{ route('provider.food-items.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg text-sm font-semibold shadow">Add New Item</a>
            </div>
        </div>
        @php
            $grouped = $foodItems->groupBy('status');
            $statuses = ['active' => 'Active Items', 'inactive' => 'Inactive Items', 'expired' => 'Expired Items'];
            $view = request('view', 'grid');
        @endphp
        @foreach($statuses as $status => $label)
            @if(isset($grouped[$status]) && $grouped[$status]->count())
                <h3 class="text-lg font-bold mt-8 mb-4">{{ $label }}</h3>
                <div class="{{ $view==='grid' ? 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6' : 'flex flex-col gap-4' }}">
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
                        <div class="bg-white rounded-xl shadow p-5 flex {{ $view==='grid' ? 'flex-col h-full' : 'flex-row items-center' }} gap-4 border border-gray-100 hover:shadow-lg transition-shadow relative">
                            <div class="flex gap-3 items-center {{ $view==='list' ? 'mb-0' : 'mb-4' }}">
                                @if(count($photos) > 0)
                                    @php
                                        $photo = $photos[0];
                                        $isUrl = Str::startsWith($photo, ['http://', 'https://']);
                                        $src = $isUrl ? $photo : asset('storage/' . ltrim($photo, '/'));
                                    @endphp
                                    <img class="w-20 h-20 object-cover rounded-lg border" src="{{ $src }}" alt="">
                                @else
                                    <div class="w-20 h-20 bg-gray-200 flex items-center justify-center rounded-lg text-gray-400 border">No Photo</div>
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
                            <div class="mt-auto flex flex-wrap gap-2 {{ $view==='list' ? 'ml-auto' : '' }}">
                                <a href="{{ route('provider.food-items.show', $item) }}" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 rounded-lg font-semibold text-xs">View</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endforeach
        @if($foodItems->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-500 mb-4">You haven't added any food items yet.</p>
                <a href="{{ route('provider.food-items.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Create Your First Item
                </a>
            </div>
        @endif
    </div>
</div>
@endsection 