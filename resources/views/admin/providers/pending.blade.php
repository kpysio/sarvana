@extends('layouts.admin')
@section('title', 'Pending Providers')
@section('content')
<div class="max-w-3xl mx-auto">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100">Pending Provider Approvals</h2>
    @if(session('status'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
    @endif
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Registered</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($pendingProviders as $provider)
                <tr>
                    <td class="px-6 py-4">{{ $provider->name }}</td>
                    <td class="px-6 py-4">{{ $provider->email }}</td>
                    <td class="px-6 py-4">{{ $provider->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 flex space-x-2">
                        <form method="POST" action="{{ route('admin.providers.approve', $provider->id) }}">
                            @csrf
                            <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">Approve</button>
                        </form>
                        <form method="POST" action="{{ route('admin.providers.reject', $provider->id) }}">
                            @csrf
                            <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Reject</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-gray-500 dark:text-gray-300 py-8">No pending providers.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection 