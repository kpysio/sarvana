<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Welcome to Sarvana!') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">
                            Welcome to Sarvana, {{ auth()->user()->name }}!
                        </h3>
                        <p class="text-gray-600 mb-6">
                            You're now registered as a food provider. Let's get you started with selling your delicious food items.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="text-center p-6 border rounded-lg">
                            <div class="bg-blue-100 rounded-full p-3 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h4 class="font-medium text-gray-900 mb-2">Complete Your Profile</h4>
                            <p class="text-sm text-gray-600">Add your bio, profile photo, and contact information</p>
                        </div>

                        <div class="text-center p-6 border rounded-lg">
                            <div class="bg-green-100 rounded-full p-3 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <h4 class="font-medium text-gray-900 mb-2">Add Food Items</h4>
                            <p class="text-sm text-gray-600">Create your first food items to start selling</p>
                        </div>

                        <div class="text-center p-6 border rounded-lg">
                            <div class="bg-yellow-100 rounded-full p-3 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h4 class="font-medium text-gray-900 mb-2">Get Verified</h4>
                            <p class="text-sm text-gray-600">Complete verification to build trust with customers</p>
                        </div>
                    </div>

                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('profile.edit') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">
                            Complete Profile
                        </a>
                        <a href="{{ route('provider.food-items.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg">
                            Add Food Item
                        </a>
                        <a href="{{ route('dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg">
                            Go to Dashboard
                        </a>
                    </div>

                    <div class="mt-8 p-4 bg-blue-50 rounded-lg">
                        <h4 class="font-medium text-blue-900 mb-2">Quick Tips for Success:</h4>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• Add high-quality photos of your food items</li>
                            <li>• Set competitive prices and clear pickup times</li>
                            <li>• Respond quickly to customer orders</li>
                            <li>• Maintain high food quality and hygiene standards</li>
                            <li>• Build your reputation through positive reviews</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 