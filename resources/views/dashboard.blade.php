<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->isProvider())
        @php
            $expiring = auth()->user()->unreadNotifications->where('type', 'App\\Notifications\\FoodItemExpiringSoon');
            $orders = auth()->user()->providerOrders()->where('status', 'completed')->get();
            $totalSales = $orders->sum('quantity');
            $totalRevenue = $orders->sum('total_amount');
            $popular = $orders->groupBy('food_item_id')->map(function($orders, $id) {
                return [
                    'count' => $orders->sum('quantity'),
                    'title' => optional($orders->first()->foodItem)->title,
                    'id' => $id
                ];
            })->sortByDesc('count')->take(3);
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow text-center">
                <div class="text-2xl font-bold">{{ $totalSales }}</div>
                <div class="text-gray-500">Total Sales</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow text-center">
                <div class="text-2xl font-bold">₹{{ number_format($totalRevenue, 2) }}</div>
                <div class="text-gray-500">Total Revenue</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow text-center">
                <div class="text-lg font-bold mb-2">Most Popular Items</div>
                <ul>
                    @forelse($popular as $item)
                        <li>
                            <a href="{{ route('customers.food-item.show', $item['id']) }}" class="underline">{{ $item['title'] ?? 'Unknown' }}</a>
                            <span class="text-xs text-gray-500">({{ $item['count'] }} sold)</span>
                        </li>
                    @empty
                        <li class="text-gray-400">No sales yet</li>
                    @endforelse
                </ul>
            </div>
        </div>
        @if($expiring->count())
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6">
                <div class="font-bold mb-2">Food Items Expiring Soon</div>
                <ul class="list-disc pl-6">
                    @foreach($expiring as $note)
                        <li>
                            <a href="{{ route('food-items.edit', $note->data['food_item_id']) }}" class="underline font-semibold">{{ $note->data['title'] }}</a>
                            expires on <span class="font-bold">{{ \Carbon\Carbon::parse($note->data['expiry_date'])->format('M d, Y') }}</span>
                            <form method="POST" action="{{ route('notifications.markAsRead', $note->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="ml-2 text-xs text-blue-600 underline">Dismiss</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endif
</x-app-layout>
