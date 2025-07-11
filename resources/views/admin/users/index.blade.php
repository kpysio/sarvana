@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">User Management</h2>
            <p class="text-gray-600">Manage users, approve providers, and control account access</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.users.index', ['export' => 'csv']) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                Export CSV
            </a>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white p-6 rounded-lg shadow mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   class="w-full border border-gray-300 rounded-md px-3 py-2" 
                   placeholder="Name or email...">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">User Type</label>
            <select name="user_type" class="w-full border border-gray-300 rounded-md px-3 py-2">
                <option value="">All Types</option>
                @foreach($userTypes as $type)
                <option value="{{ $type }}" {{ request('user_type') == $type ? 'selected' : '' }}>
                    {{ ucfirst($type) }}
                </option>
                @endforeach
            </select>
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
        <div class="flex items-end">
            <button type="submit" class="w-full bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700">
                Filter
            </button>
        </div>
    </form>
</div>

<!-- Bulk Actions -->
<div class="bg-white p-4 rounded-lg shadow mb-6">
    <form method="POST" action="{{ route('admin.users.bulk-action') }}" id="bulk-form">
        @csrf
        <div class="flex items-center space-x-4">
            <select name="action" class="border border-gray-300 rounded-md px-3 py-2">
                <option value="">Bulk Actions</option>
                <option value="approve">Approve Providers</option>
                <option value="reject">Reject Providers</option>
                <option value="deactivate">Deactivate Accounts</option>
                <option value="reactivate">Reactivate Accounts</option>
                <option value="delete">Delete Users</option>
            </select>
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700" 
                    onclick="return confirm('Are you sure you want to perform this action?')">
                Apply
            </button>
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Users ({{ $users->total() }})</h3>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($users as $user)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" name="users[]" value="{{ $user->id }}" 
                               class="bulk-select rounded border-gray-300">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span class="text-sm font-medium text-gray-700">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                            @if($user->user_type === 'admin') bg-purple-100 text-purple-800
                            @elseif($user->user_type === 'provider') bg-orange-100 text-orange-800
                            @else bg-blue-100 text-blue-800
                            @endif">
                            {{ ucfirst($user->user_type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                            @if($user->membership_status === 'active') bg-green-100 text-green-800
                            @elseif($user->membership_status === 'expired') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ ucfirst($user->membership_status ?? 'pending') }}
                        </span>
                        @if($user->is_verified)
                        <span class="ml-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            Verified
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.users.show', $user) }}" 
                               class="text-blue-600 hover:text-blue-900">View</a>
                            <a href="{{ route('admin.users.edit', $user) }}" 
                               class="text-green-600 hover:text-green-900">Edit</a>
                            
                            @if($user->user_type === 'provider')
                                @if($user->membership_status !== 'active')
                                <form method="POST" action="{{ route('admin.users.approve', $user) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900">Approve</button>
                                </form>
                                @else
                                <form method="POST" action="{{ route('admin.users.reject', $user) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-900">Reject</button>
                                </form>
                                @endif
                            @endif
                            
                            @if($user->user_type !== 'admin')
                                @if($user->membership_status === 'active')
                                <form method="POST" action="{{ route('admin.users.deactivate', $user) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-900">Deactivate</button>
                                </form>
                                @else
                                <form method="POST" action="{{ route('admin.users.reactivate', $user) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900">Reactivate</button>
                                </form>
                                @endif
                                
                                <form method="POST" action="{{ route('admin.users.reset-password', $user) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-purple-600 hover:text-purple-900">Reset Password</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $users->links() }}
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
</script>
@endsection 