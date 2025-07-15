@extends('layouts.provider')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">Provider Dashboard</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 flex flex-col items-center">
            <div class="text-3xl font-bold text-green-600 dark:text-green-300">{{ $totalItems ?? 0 }}</div>
            <div class="text-gray-600 dark:text-gray-300 mt-2">Total Items</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 flex flex-col items-center">
            <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-300">{{ $totalOrders ?? 0 }}</div>
            <div class="text-gray-600 dark:text-gray-300 mt-2">Total Orders</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 flex flex-col items-center">
            <div class="text-3xl font-bold text-blue-600 dark:text-blue-300">£{{ number_format($totalEarnings ?? 0, 2) }}</div>
            <div class="text-gray-600 dark:text-gray-300 mt-2">Total Earnings</div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Welcome, {{ Auth::user()->name }}!</h2>
        <p class="text-gray-600 dark:text-gray-300">This is your modern provider dashboard. Use the sidebar to manage your store and orders.</p>
    </div>
</div>
@endsection 