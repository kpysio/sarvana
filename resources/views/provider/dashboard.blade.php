@extends('layouts.provider')

@section('content')
<div class="max-w-3xl mx-auto mt-8">
    <div class="bg-white rounded shadow p-8 text-center">
        <h1 class="text-2xl font-bold mb-2">Welcome, {{ Auth::user()->name }}!</h1>
        <p class="text-gray-600">This is your modern provider dashboard. Use the sidebar to manage your store and orders.</p>
    </div>
</div>
@endsection 