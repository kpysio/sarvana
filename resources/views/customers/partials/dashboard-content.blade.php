<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-8 mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Welcome, {{ Auth::user()->name }}!</h1>
        <p class="text-gray-600 dark:text-gray-300 mb-6">Here you can view your active orders, order history, and favorite items. Use the navigation bar above to search for new food items or view your orders.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-xl p-6 flex flex-col items-center border border-orange-200 dark:border-orange-700">
                <div class="text-4xl mb-3">🍽️</div>
                <div class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Active Orders</div>
                <div class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $activeOrdersCount ?? 0 }}</div>
            </div>
            
            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-6 flex flex-col items-center border border-green-200 dark:border-green-700">
                <div class="text-4xl mb-3">✅</div>
                <div class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Completed Orders</div>
                <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $completedOrdersCount ?? 0 }}</div>
            </div>
            
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-xl p-6 flex flex-col items-center border border-yellow-200 dark:border-yellow-700">
                <div class="text-4xl mb-3">⭐</div>
                <div class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Favorites</div>
                <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $favoritesCount ?? 0 }}</div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('search.index') }}" class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-700 hover:bg-blue-100 dark:hover:bg-blue-800/30 transition-colors">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900 dark:text-white">Search Food Items</div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">Find and order delicious food</div>
                    </div>
                </a>
                
                <a href="{{ route('customers.orders.index') }}" class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-700 hover:bg-green-100 dark:hover:bg-green-800/30 transition-colors">
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900 dark:text-white">View My Orders</div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">Track your order status</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div> 