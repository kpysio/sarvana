@extends('layouts.provider')

@section('content')
<!-- Add SortableJS and canvas-confetti CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<div class="max-w-7xl mx-auto mt-8 font-sans">
    <div class="flex flex-col md:flex-row gap-4 overflow-x-auto">
        @php
            $statuses = [
                'pending' => ['New Orders', 'bg-yellow-50', 'text-yellow-700', 'ClockIcon'],
                'preparing' => ['Preparing', 'bg-blue-50', 'text-blue-700', 'FireIcon'],
                'ready' => ['Ready', 'bg-green-50', 'text-green-700', 'CheckCircleIcon'],
                'completed' => ['Completed', 'bg-gray-50', 'text-gray-700', 'BadgeCheckIcon'],
                'cancelled' => ['Cancelled', 'bg-red-50', 'text-red-700', 'XCircleIcon'],
            ];
            $icons = [
                'ClockIcon' => '<svg class="w-5 h-5 mr-1 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'FireIcon' => '<svg class="w-5 h-5 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2m0 0C7.03 5 3 9.03 3 14c0 3.866 3.134 7 7 7s7-3.134 7-7c0-4.97-4.03-9-9-9z" /></svg>',
                'CheckCircleIcon' => '<svg class="w-5 h-5 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'BadgeCheckIcon' => '<svg class="w-5 h-5 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                'XCircleIcon' => '<svg class="w-5 h-5 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
            ];
        @endphp
        @foreach($statuses as $status => [$label, $bg, $text, $icon])
        <div class="order-column flex-1 min-w-[260px] {{ $bg }} rounded-xl shadow-lg p-4 transition-all duration-200" data-status="{{ $status }}">
            <div class="flex items-center font-bold text-lg mb-3 {{ $text }}">
                {!! $icons[$icon] !!} {{ $label }}
            </div>
            <div class="space-y-3 min-h-[80px]" id="orders-{{ $status }}">
                @if(isset($orders[$status]))
                    @foreach($orders[$status] as $order)
                        <div class="order-card bg-white rounded-lg shadow hover:shadow-xl p-4 cursor-move border-l-4 border-transparent hover:border-blue-400 transition-all duration-150 group" data-order-id="{{ $order->id }}">
                            <div class="flex items-center justify-between">
                                <div class="font-semibold text-base text-gray-800">Order #{{ $order->id }}</div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $text }} bg-opacity-10 {{ $bg }}">
                                    {!! $icons[$icon] !!} {{ ucfirst($status) }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-600 mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.485 0 4.847.657 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                {{ $order->customer->name ?? 'N/A' }}
                            </div>
                            <div class="text-xs text-blue-700 font-semibold mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2h-4V3a1 1 0 00-2 0v2H6a2 2 0 00-2 2v6" /></svg>
                                {{ $order->foodItem->title ?? 'N/A' }}
                            </div>
                            <div class="text-xs text-gray-400 mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 4h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2z" /></svg>
                                {{ $order->created_at->diffForHumans() }}
                            </div>
                            <button @click="window.dispatchEvent(new CustomEvent('open-order-modal', { detail: {{ $order->id }} }))" class="mt-3 text-blue-600 hover:underline text-xs font-medium focus:outline-none focus:ring-2 focus:ring-blue-400 rounded transition">Details</button>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        @endforeach
    </div>
    <!-- Order Details Modal -->
    <div x-data="orderBoard()" x-init="window.addEventListener('open-order-modal', e => { showOrder(e.detail); });" x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 transition-opacity duration-200">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-2 p-6 relative animate-fade-in" @click.away="closeModal()" @keydown.escape.window="closeModal()" tabindex="0">
            <button @click="closeModal()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
            <template x-if="loading">
                <div class="text-center py-8">
                    <svg class="animate-spin h-8 w-8 text-blue-600 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>
                    <div class="text-gray-600">Loading...</div>
                </div>
            </template>
            <template x-if="error">
                <div class="text-center py-8 text-red-600">Failed to load order details.</div>
            </template>
            <template x-if="!loading && !error && order">
                <div class="space-y-2">
                    <h2 class="text-2xl font-bold mb-2 text-blue-700 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Order #<span x-text="order.id"></span>
                    </h2>
                    <div class="flex items-center text-gray-700"><svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.485 0 4.847.657 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" /></svg> <span x-text="order.customer?.name ?? 'N/A'"></span></div>
                    <div class="flex items-center text-gray-700"><svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 4h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2z" /></svg> <span x-text="order.created_at"></span></div>
                    <div class="flex items-center text-gray-700"><svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2h-4V3a1 1 0 00-2 0v2H6a2 2 0 00-2 2v6" /></svg> <span x-text="order.food_item?.title ?? 'N/A'"></span></div>
                    <div class="flex items-center text-gray-700"><svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V5a1 1 0 00-1-1H9a1 1 0 00-1 1v6m8 0a4 4 0 01-8 0" /></svg> <span x-text="order.quantity"></span></div>
                    <div class="flex items-center text-gray-700"><svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 4h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2z" /></svg> <span x-text="order.pickup_time ?? 'Not set'"></span></div>
                    <div class="flex items-center text-gray-700"><svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V10a2 2 0 012-2h2" /></svg> <span x-text="order.customer_notes ?? '-' "></span></div>
                    <div class="mt-4">
                        <a :href="`/provider/orders/${order.id}`" class="text-blue-600 hover:underline text-sm">View Full Details</a>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
<script>
function orderBoard() {
    return {
        modalOpen: false,
        loading: false,
        error: false,
        order: null,
        showOrder(orderId) {
            this.modalOpen = true;
            this.loading = true;
            this.error = false;
            this.order = null;
            fetch(`/provider/orders/${orderId}`)
                .then(res => {
                    if (!res.ok) throw new Error('Failed to load');
                    return res.text();
                })
                .then(html => {
                    // Try to extract JSON from a <script type="application/json" id="order-json"> tag in the show view
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const jsonScript = doc.getElementById('order-json');
                    if (jsonScript) {
                        this.order = JSON.parse(jsonScript.textContent);
                        this.loading = false;
                    } else {
                        throw new Error('No order data');
                    }
                })
                .catch(() => {
                    this.loading = false;
                    this.error = true;
                });
        },
        closeModal() {
            this.modalOpen = false;
        },
        init() {
            // This function is no longer needed as SortableJS is initialized in the Blade template
        }
    }
}
document.addEventListener('alpine:init', () => {
    Alpine.data('orderBoard', orderBoard);
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.order-column .space-y-3').forEach(column => {
        new Sortable(column, {
            group: 'orders',
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: function (evt) {
                const orderId = evt.item.dataset.orderId;
                const newStatus = evt.to.parentElement.dataset.status;
                evt.item.classList.add('opacity-50');
                fetch(`/provider/orders/${orderId}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(res => res.json())
                .then(data => {
                    evt.item.classList.remove('opacity-50');
                    if (!data.success) {
                        alert('Failed to update order status.');
                    } else {
                        // Move the card to the new column in the DOM
                        const targetColumn = document.querySelector(`.order-column[data-status='${newStatus}'] .space-y-3`);
                        if (targetColumn && evt.item.parentElement !== targetColumn) {
                            targetColumn.appendChild(evt.item);
                        }
                        // Update the status label and color in the card
                        const statusLabels = {
                            'pending':  { label: 'New Orders', bg: 'bg-yellow-50', text: 'text-yellow-700', icon: `{!! $icons['ClockIcon'] !!}` },
                            'preparing': { label: 'Preparing', bg: 'bg-blue-50', text: 'text-blue-700', icon: `{!! $icons['FireIcon'] !!}` },
                            'ready':     { label: 'Ready', bg: 'bg-green-50', text: 'text-green-700', icon: `{!! $icons['CheckCircleIcon'] !!}` },
                            'completed': { label: 'Completed', bg: 'bg-gray-50', text: 'text-gray-700', icon: `{!! $icons['BadgeCheckIcon'] !!}` },
                            'cancelled': { label: 'Cancelled', bg: 'bg-red-50', text: 'text-red-700', icon: `{!! $icons['XCircleIcon'] !!}` },
                        };
                        const label = statusLabels[newStatus];
                        if (label) {
                            const statusSpan = evt.item.querySelector('span.inline-flex');
                            if (statusSpan) {
                                statusSpan.className = `inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ${label.text} bg-opacity-10 ${label.bg}`;
                                statusSpan.innerHTML = label.icon + ' ' + label.label;
                            }
                        }
                        if (newStatus === 'completed' && window.confetti) {
                            confetti({
                                particleCount: 100,
                                spread: 70,
                                origin: { y: 0.6 }
                            });
                        }
                    }
                })
                .catch(() => {
                    evt.item.classList.remove('opacity-50');
                    alert('Failed to update order status.');
                });
            }
        });
    });
});
</script>
@endsection 