@extends('layouts.admin')
@section('title', 'Notification')
@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-100">Notification</h2>
        <div class="mb-4 text-gray-700 dark:text-gray-200">
            {{ $notification->data['message'] ?? 'Notification' }}
        </div>
        <div class="text-xs text-gray-500 dark:text-gray-400 mb-4">
            {{ $notification->created_at->format('M d, Y H:i') }}
        </div>
        <a href="{{ route('admin.notifications.index') }}" class="text-blue-600 hover:underline">&larr; Back to notifications</a>
    </div>
</div>
@endsection 