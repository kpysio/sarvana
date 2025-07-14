@extends('layouts.admin')
@section('title', 'Notifications')
@section('content')
<div class="max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100">Notifications</h2>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($notifications as $notification)
                <li class="flex items-start p-4 hover:bg-gray-100 dark:hover:bg-gray-700 {{ $notification->read_at ? 'opacity-60' : '' }}">
                    <span class="w-2 h-2 {{ $notification->read_at ? 'bg-gray-400' : 'bg-blue-500' }} rounded-full mt-2 mr-3"></span>
                    <div class="flex-1">
                        <a href="{{ route('admin.notifications.show', $notification->id) }}" class="block {{ $notification->read_at ? 'text-gray-700 dark:text-gray-300' : 'font-semibold text-gray-800 dark:text-gray-100' }}">
                            {{ $notification->data['message'] ?? 'Notification' }}
                        </a>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $notification->created_at->diffForHumans() }}</div>
                    </div>
                </li>
            @empty
                <li class="p-4 text-gray-500 dark:text-gray-400">No notifications found.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection 