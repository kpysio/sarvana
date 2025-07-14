@extends('layouts.admin')
@section('title', 'Profile')
@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center space-x-4 mb-6">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'Admin') }}" class="w-16 h-16 rounded-full" alt="Avatar">
            <div>
                <div class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $user->name ?? 'Admin' }}</div>
                <div class="text-gray-500 dark:text-gray-400">{{ $user->email ?? '' }}</div>
            </div>
        </div>
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">Profile Settings</h3>
        <div class="text-gray-600 dark:text-gray-300 mb-4">(Profile settings form coming soon...)</div>
        <a href="{{ url()->previous() }}" class="text-blue-600 hover:underline">&larr; Back</a>
    </div>
</div>
@endsection 