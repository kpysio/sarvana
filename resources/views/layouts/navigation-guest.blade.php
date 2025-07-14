<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0 flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-400 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-utensils text-white"></i>
                    </div>
                    <span class="text-xl font-bold text-gray-800">FreshLocal</span>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-purple-600 font-medium">Login</a>
                <a href="{{ route('register') }}" class="hero-gradient text-white px-4 py-2 rounded-lg font-medium hover:opacity-90 transition-opacity">Sign Up</a>
                <a href="{{ route('register.provider') }}" class="border-2 border-purple-600 text-purple-600 px-4 py-2 rounded-lg font-medium hover:bg-purple-50 transition-colors ml-2">Provider Sign Up</a>
            </div>
        </div>
    </div>
</nav> 