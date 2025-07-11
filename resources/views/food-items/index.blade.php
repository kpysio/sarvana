<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Store') }}
            </h2>
            @auth
                @if(auth()->user()->user_type === 'provider')
                    <a href="{{ route('food-items.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Add New Item
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @php
                $grouped = $foodItems->groupBy('status');
            @endphp
            @foreach(['active' => 'Active Items', 'inactive' => 'Inactive Items', 'expired' => 'Expired Items'] as $status => $label)
                @if(isset($grouped[$status]) && $grouped[$status]->count())
                    <h3 class="text-lg font-bold mt-8 mb-2">{{ $label }}</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 mb-6">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiry</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
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
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if(count($photos) > 0)
                                                    @php
                                                        $photo = $photos[0];
                                                        $isUrl = Str::startsWith($photo, ['http://', 'https://']);
                                                        $src = $isUrl ? $photo : asset('storage/' . ltrim($photo, '/'));
                                                    @endphp
                                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ $src }}" alt="">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                        <span class="text-gray-400 text-xs">No Image</span>
                                                    </div>
                                                @endif
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $item->title }}</div>
                                                    <div class="text-sm text-gray-500">{{ Str::limit($item->description, 50) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ucfirst($item->category) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">₹{{ $item->price }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->available_quantity }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $item->status === 'active' ? 'bg-green-100 text-green-800' : 
                                                   ($item->status === 'inactive' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $now = \Carbon\Carbon::now();
                                                $expiry = $item->expiry_date ?? $item->available_date;
                                                $expired = $item->status === 'expired' || ($expiry && \Carbon\Carbon::parse($expiry)->isPast());
                                                $daysLeft = $expiry ? \Carbon\Carbon::parse($expiry)->diffInDays($now, false) : null;
                                            @endphp
                                            @if($expired)
                                                <span class="text-red-600 font-bold">Expired</span>
                                            @elseif($expiry)
                                                <span class="countdown" data-expiry="{{ \Carbon\Carbon::parse($expiry)->format('Y-m-d H:i:s') }}">
                                                    <span class="{{ $daysLeft <= 2 ? 'text-red-600 font-bold' : 'text-gray-800' }}">
                                                        Expires in <span class="countdown-timer"></span>
                                                    </span>
                                                </span>
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('food-items.show', $item) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                            <a href="{{ route('food-items.edit', $item) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                            <a href="{{ route('food-items.create', ['clone_id' => $item->id]) }}" class="text-green-600 hover:text-green-900 mr-3">Clone</a>
                                            @if($item->status === 'expired' || $item->status === 'inactive')
                                                <form action="{{ route('food-items.reactivate', $item) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-blue-600 hover:text-blue-900 mr-2">Reactivate</button>
                                                </form>
                                            @endif
                                            <form action="{{ route('food-items.extendExpiry', $item) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="number" name="days" min="1" max="365" value="7" class="w-14 text-xs border rounded px-1 mr-1" style="height: 1.5em;" />
                                                <button type="submit" class="text-yellow-600 hover:text-yellow-900 mr-2">Extend</button>
                                            </form>
                                            @if($item->status === 'active')
                                                <form action="{{ route('food-items.markSoldOut', $item) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Mark Sold Out</button>
                                                </form>
                                            @endif
                                            <form action="{{ route('food-items.destroy', $item) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @endforeach
            @if($foodItems->isEmpty())
                <div class="text-center py-12">
                    <p class="text-gray-500 mb-4">You haven't added any food items yet.</p>
                    <a href="{{ route('food-items.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Create Your First Item
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 
<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateCountdowns() {
        document.querySelectorAll('.countdown').forEach(function(el) {
            const expiry = new Date(el.getAttribute('data-expiry').replace(/-/g, '/'));
            const now = new Date();
            let diff = expiry - now;
            if (diff <= 0) {
                el.innerHTML = '<span class="text-red-600 font-bold">Expired</span>';
                return;
            }
            const days = Math.floor(diff / (1000*60*60*24));
            const hours = Math.floor((diff / (1000*60*60)) % 24);
            const mins = Math.floor((diff / (1000*60)) % 60);
            let str = '';
            if (days > 0) str += days + 'd ';
            if (hours > 0 || days > 0) str += hours + 'h ';
            str += mins + 'm';
            el.querySelector('.countdown-timer').textContent = str;
        });
    }
    setInterval(updateCountdowns, 60000);
    updateCountdowns();
});
</script> 