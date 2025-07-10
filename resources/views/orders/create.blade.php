<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Place Order') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-center py-12">
                        <p class="text-gray-500 mb-4">Please browse food items to place an order.</p>
                        <a href="{{ route('browse.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            Browse Food Items
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 