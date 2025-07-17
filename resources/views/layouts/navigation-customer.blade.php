<nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">F</span>
                        </div>
                        <span class="text-xl font-bold text-gray-900 dark:text-white">FreshLocal</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:ml-10 md:flex md:space-x-8">
                    @auth
                        <a href="{{ route('search.index') }}" class="text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('search.*') ? 'text-gray-900 dark:text-white bg-orange-100 dark:bg-orange-900/30' : '' }}">
                            Search
                        </a>
                        <a href="{{ route('customers.orders.index') }}" class="text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->is('customer/orders*') ? 'text-gray-900 dark:text-white bg-orange-100 dark:bg-orange-900/30' : '' }}">
                            My Orders
                        </a>
                        <a href="{{ route('customers.dashboard') }}" class="text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('customers.dashboard') ? 'text-gray-900 dark:text-white bg-orange-100 dark:bg-orange-900/30' : '' }}">
                            Dashboard
                        </a>
                    @endauth
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
                <!-- Enhanced Notifications Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="relative focus:outline-none">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full text-xs px-1">3</span>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 shadow-lg rounded-lg z-50" style="display: none;">
                        <div class="flex items-center justify-between p-4 border-b dark:border-gray-700 font-semibold">
                            <span class="text-gray-800 dark:text-gray-100">Notifications</span>
                            <button class="text-xs text-blue-600 hover:underline" @click="/* mark all as read logic */">Mark all as read</button>
                        </div>
                        <ul class="max-h-80 overflow-y-auto">
                            <!-- Example notification items -->
                            <li class="flex items-start p-4 hover:bg-gray-100 dark:hover:bg-gray-700 border-b dark:border-gray-700">
                                <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3"></span>
                                <div class="flex-1">
                                    <a href="#" class="font-semibold text-gray-800 dark:text-gray-100">New order placed</a>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">2 min ago</div>
                                </div>
                            </li>
                            <li class="flex items-start p-4 hover:bg-gray-100 dark:hover:bg-gray-700 border-b dark:border-gray-700 opacity-60">
                                <span class="w-2 h-2 bg-gray-400 rounded-full mt-2 mr-3"></span>
                                <div class="flex-1">
                                    <a href="#" class="text-gray-700 dark:text-gray-300">Provider approved</a>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">10 min ago</div>
                                </div>
                            </li>
                            <li class="flex items-start p-4 hover:bg-gray-100 dark:hover:bg-gray-700 border-b dark:border-gray-700">
                                <span class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3"></span>
                                <div class="flex-1">
                                    <a href="#" class="font-semibold text-gray-800 dark:text-gray-100">User registered</a>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">30 min ago</div>
                                </div>
                            </li>
                        </ul>
                        <div class="p-2 text-center">
                            <a href="#" class="text-blue-600 hover:underline text-sm">View all notifications</a>
                        </div>
                    </div>
                </div>
                <!-- Email -->
                <button class="relative focus:outline-none">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8V8a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                    <span class="absolute -top-1 -right-1 bg-blue-500 text-white rounded-full text-xs px-1">5</span>
                </button>
                <!-- Enhanced User Menu -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center focus:outline-none">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}" class="w-8 h-8 rounded-full mr-2" alt="User Avatar">
                        <span class="font-semibold text-gray-700 dark:text-gray-200">{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4 ml-1 text-gray-600 dark:text-gray-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 shadow-lg rounded-lg z-50" style="display: none;">
                        <div class="p-4 border-b dark:border-gray-700">
                            <div class="flex items-center space-x-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}" class="w-10 h-10 rounded-full" alt="Avatar">
                                <div>
                                    <div class="font-semibold text-gray-800 dark:text-gray-100">{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">Profile</a>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">Settings</a>
                        <div class="border-t dark:border-gray-700 my-2"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav> 