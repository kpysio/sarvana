@extends('layouts.provider')

@section('content')
<div class="max-w-lg mx-auto mt-10 bg-white rounded shadow p-8">
    <h1 class="text-2xl font-bold mb-4 text-gray-800">Place Order for: <span class="text-blue-600">{{ $foodItem->title }}</span></h1>
    <form method="POST" action="{{ route('provider.food-items.placeOrder', $foodItem->id) }}">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
            <input type="text" name="customer_name" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
            <input type="text" name="customer_phone" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
            <input type="number" name="quantity" min="1" max="{{ $foodItem->available_quantity }}" class="w-full border border-gray-300 rounded-md px-3 py-2" value="1" required>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Special Instruction</label>
            <textarea name="special_instruction" class="w-full border border-gray-300 rounded-md px-3 py-2" rows="2"></textarea>
        </div>
        <div class="flex justify-end gap-2">
            <a href="{{ route('provider.food-items.show', $foodItem->id) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-700">Place Order</button>
        </div>
    </form>
</div>
@endsection 