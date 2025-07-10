<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update Order Status') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Order #{{ $order->id }}</h3>
                        <p class="text-gray-600">{{ $order->foodItem->title }} - {{ $order->customer->name }}</p>
                    </div>

                    <form method="POST" action="{{ route('orders.update', $order) }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Current Status -->
                            <div>
                                <x-input-label for="current_status" :value="__('Current Status')" />
                                <p class="mt-1 text-gray-900">{{ ucfirst($order->status) }}</p>
                            </div>

                            <!-- New Status -->
                            <div>
                                <x-input-label for="status" :value="__('New Status')" />
                                <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <!-- Notes -->
                            <div>
                                <x-input-label for="notes" :value="__('Notes (Optional)')" />
                                <textarea id="notes" name="notes" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes', $order->notes) }}</textarea>
                                <p class="text-sm text-gray-500 mt-1">Add any notes for the customer about this order.</p>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>

                            <!-- Order Summary -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-2">Order Summary</h4>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500">Customer:</span>
                                        <p class="text-gray-900">{{ $order->customer->name }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Item:</span>
                                        <p class="text-gray-900">{{ $order->foodItem->title }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Quantity:</span>
                                        <p class="text-gray-900">{{ $order->quantity }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Total Amount:</span>
                                        <p class="text-gray-900 font-medium">₹{{ $order->total_amount }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Pickup Time:</span>
                                        <p class="text-gray-900">{{ $order->pickup_time->format('M d, Y g:i A') }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Order Date:</span>
                                        <p class="text-gray-900">{{ $order->created_at->format('M d, Y g:i A') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end space-x-4">
                                <a href="{{ route('orders.show', $order) }}" class="text-gray-600 hover:text-gray-800">
                                    Cancel
                                </a>
                                <x-primary-button>
                                    {{ __('Update Order') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 