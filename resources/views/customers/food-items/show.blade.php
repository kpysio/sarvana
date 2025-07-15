@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <a href="{{ url()->previous() }}" class="text-blue-600 hover:underline mb-4 inline-block">&larr; Back</a>
    <div class="bg-white rounded shadow p-6 mb-6">
        <h1 class="text-2xl font-bold mb-2">{{ $foodItem->title }}</h1>
        <div class="mb-2">Category: <span class="font-semibold">{{ $foodItem->category ?? '-' }}</span></div>
        <div class="mb-2">Provider: <span class="font-semibold">{{ $foodItem->provider->name ?? '-' }}</span></div>
        <div class="mb-2">Price: <span class="font-semibold">₹{{ number_format($foodItem->price, 2) }}</span></div>
        <div class="mb-2">Available Quantity: {{ $foodItem->available_quantity }}</div>
        <div class="mb-2">Available Date: {{ $foodItem->available_date ? \Carbon\Carbon::parse($foodItem->available_date)->format('M d, Y') : '-' }}</div>
        <div class="mb-2">Pickup Address: {{ $foodItem->pickup_address ?? '-' }}</div>
        <div class="mb-2">Tags: 
            @foreach($foodItem->tags as $tag)
                <span class="inline-block bg-gray-200 rounded px-2 py-1 text-xs mr-1">{{ $tag->name }}</span>
            @endforeach
        </div>
        <div class="mb-2">Description: {{ $foodItem->description }}</div>
    </div>
    <div class="bg-white rounded shadow p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Reviews</h2>
        @if($foodItem->reviews->count())
            <ul>
                @foreach($foodItem->reviews as $review)
                    <li class="mb-2 border-b pb-2">
                        <div class="font-semibold">{{ $review->reviewer->name ?? 'User' }}</div>
                        <div class="text-sm text-gray-600">{{ $review->created_at->format('M d, Y') }}</div>
                        <div>{{ $review->content }}</div>
                        <div class="text-yellow-500">Rating: {{ $review->rating }}/5</div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-gray-500">No reviews yet.</div>
        @endif
    </div>
    <div class="bg-white rounded shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Order This Item</h2>
        @auth
            @if(auth()->user()->isCustomer())
                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="food_item_id" value="{{ $foodItem->id }}">
                    <input type="hidden" name="provider_id" value="{{ $foodItem->provider_id }}">
                    <div class="mb-4">
                        <label for="quantity" class="block font-semibold mb-1">Quantity</label>
                        <input type="number" name="quantity" id="quantity" min="1" max="{{ $foodItem->available_quantity }}" value="1" class="border rounded px-2 py-1 w-24">
                    </div>
                    <div class="mb-4">
                        <label for="customer_notes" class="block font-semibold mb-1">Special Instructions</label>
                        <textarea name="customer_notes" id="customer_notes" rows="2" class="border rounded px-2 py-1 w-full"></textarea>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Place Order</button>
                </form>
            @else
                <div class="text-gray-600">Only customers can place orders.</div>
            @endif
        @else
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login to order</a>
        @endauth
    </div>
</div>
@endsection 