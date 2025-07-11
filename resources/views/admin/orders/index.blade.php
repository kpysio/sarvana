@extends('layouts.admin')

@section('title', 'Order Management')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Order Management</h2>
            <p class="text-gray-600">View and manage all orders across the platform</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.orders.analytics') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Analytics
            </a>
            <a href="{{ route('admin.orders.export') }}?{{ http_build_query(request()->all()) }}" 
               class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                Export CSV
            </a>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white p-6 rounded-lg shadow mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   class="w-full border border-gray-300 rounded-md px-3 py-2" 
                   placeholder="Customer, provider, or food item...">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2">
                <option value="">All Status</option>
                @foreach($statuses as $status)
                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                    {{ ucfirst($status) }}
                </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                   class="w-full border border-gray-300 rounded-md px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                   class="w-full border border-gray-300 rounded-md px-3 py-2">
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700">
                Filter
            </button>
        </div>
    </form>
</div>

<!-- Bulk Actions -->
<div class="bg-white p-4 rounded-lg shadow mb-6">
    <form method="POST" action="{{ route('admin.orders.bulk-update-status') }}" id="bulk-form">
        @csrf
        <div class="flex items-center space-x-4">
            <select name="status" class="border border-gray-300 rounded-md px-3 py-2">
                <option value="">Update Status To</option>
                @foreach($statuses as $status)
                <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700" 
                    onclick="return confirm('Are you sure you want to update the selected orders?')">
                Update Status
            </button>
        </div>
    </form>
</div>

<!-- Orders Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Orders ({{ $orders->total() }})</h3>
            <div class="flex items-center space-x-2">
                <input type="checkbox" id="select-all" class="rounded border-gray-300">
                <label for="select-all" class="text-sm text-gray-600">Select All</label>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" class="bulk-select rounded border-gray-300">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provider</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Food Item</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($orders as $order)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" name="orders[]" value="{{ $order->id }}" 
                               class="bulk-select rounded border-gray-300">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-medium text-gray-900">#{{ $order->id }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $order->customer->name }}</div>
                            <div class="text-sm text-gray-500">{{ $order->customer->email }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $order->provider->name }}</div>
                            <div class="text-sm text-gray-500">{{ $order->provider->email }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $order->foodItem->title }}</div>
                            <div class="text-sm text-gray-500">{{ $order->quantity }} items</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-medium text-gray-900">₹{{ number_format($order->total_amount, 2) }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                            @if($order->status === 'completed') bg-green-100 text-green-800
                            @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                            @else bg-blue-100 text-blue-800
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $order->created_at->format('M d, Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.orders.show', $order) }}" 
                               class="text-blue-600 hover:text-blue-900">View</a>
                            <button onclick="updateStatus({{ $order->id }}, '{{ $order->status }}')" 
                                    class="text-green-600 hover:text-green-900">Update</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $orders->links() }}
    </div>
</div>

<!-- Update Status Modal -->
<div id="status-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Update Order Status</h3>
            </div>
            <form method="POST" id="status-form" class="p-6">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">New Status</label>
                    <select id="status" name="status" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        @foreach($statuses as $status)
                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea id="notes" name="notes" rows="3" 
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500"
                              placeholder="Add any notes about this status change..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeStatusModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select-all');
    const bulkSelects = document.querySelectorAll('.bulk-select');
    
    selectAll.addEventListener('change', function() {
        bulkSelects.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    
    bulkSelects.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(bulkSelects).every(cb => cb.checked);
            selectAll.checked = allChecked;
        });
    });
});

function updateStatus(orderId, currentStatus) {
    const modal = document.getElementById('status-modal');
    const form = document.getElementById('status-form');
    const statusSelect = document.getElementById('status');
    
    form.action = `/admin/orders/${orderId}/status`;
    statusSelect.value = currentStatus;
    modal.classList.remove('hidden');
}

function closeStatusModal() {
    const modal = document.getElementById('status-modal');
    modal.classList.add('hidden');
}
</script>
@endsection 