<nav class="bg-white shadow-sm sticky top-0 z-50">
    <style>
        .hero-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    </style>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
            <div class="flex-shrink-0 flex items-center">
                        <div class="w-8 h-8 hero-gradient rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-utensils text-white"></i>
                        </div>
                        <span class="text-xl font-bold text-gray-800">FreshLocal</span>
                    </div>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('search.index') }}" class="text-white bg-green-600 hover:bg-green-700 font-semibold px-4 py-2 rounded-lg flex items-center gap-2 shadow transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
                    Find Food
                </a>
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-purple-600 font-medium">Login</a>
                <a href="{{ route('register') }}" class="hero-gradient text-white px-4 py-2 rounded-lg font-medium hover:opacity-90 transition-opacity">Sign Up</a>
                <a href="{{ route('register.provider') }}" class="hero-gradient text-white px-4 py-2 rounded-lg font-medium hover:opacity-90 transition-opacity ml-2">Provider Sign Up</a>
            </div>
        </div>
    </div>
</nav> 