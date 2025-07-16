<nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">S</span>
                        </div>
                        <span class="text-xl font-bold text-gray-900 dark:text-white">Sarvana</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:ml-10 md:flex md:space-x-8">
                    <a href="{{ route('search.index') }}" class="text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('search.*') ? 'text-gray-900 dark:text-white bg-orange-100 dark:bg-orange-900/30' : '' }}">
                        Search
                    </a>
                    <a href="{{ route('login') }}" class="text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        Register
                    </a>
                </div>
            </div>

            <!-- Right side navigation items -->
            <div class="flex items-center space-x-6">
                <!-- Dark/Light Mode Toggle -->
                <button @click="dark = !dark" class="focus:outline-none" :aria-label="dark ? 'Switch to light mode' : 'Switch to dark mode'" aria-label="Switch to dark mode">
                    <template x-if="!dark">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m8.66-13.66l-.71.71M4.05 19.95l-.71.71M21 12h-1M4 12H3m16.66 5.66l-.71-.71M4.05 4.05l-.71-.71M12 7a5 5 0 100 10 5 5 0 000-10z"></path></svg>
                    </template>
                    <template x-if="dark">
                        <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z"></path></svg>
                    </template>
                </button>
                
                <!-- Guest Actions -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-3 py-2 text-sm font-medium transition-colors">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        Sign Up
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav> 