@extends('layouts.admin')
@section('title', 'Settings')
@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">Settings</h2>
        <div class="text-gray-600 dark:text-gray-300 mb-4">(Settings form coming soon...)</div>
        <a href="{{ url()->previous() }}" class="text-blue-600 hover:underline">&larr; Back</a>
    </div>
</div>
@endsection 