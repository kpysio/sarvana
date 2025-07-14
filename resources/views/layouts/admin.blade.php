<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - @yield('title', 'Sarvana')</title>
    @vite('resources/css/app.css')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body x-data="{
        dark: localStorage.getItem('darkMode') === 'true',
        toggle() {
            this.dark = !this.dark;
            localStorage.setItem('darkMode', this.dark);
            document.documentElement.classList.toggle('dark', this.dark);
        }
    }"
    x-init="document.documentElement.classList.toggle('dark', dark)"
    :class="{ 'dark': dark }"
    class="bg-gray-100 dark:bg-gray-900 font-sans antialiased">
<div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-white dark:bg-gray-800 shadow-lg flex flex-col">
        <div class="h-20 flex items-center justify-center border-b dark:border-gray-700">
            <span class="text-2xl font-bold text-blue-700 dark:text-blue-300">Sarvana Admin</span>
        </div>
        <nav class="flex-1 py-6 px-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-blue-100 dark:hover:bg-gray-700">
                <svg class="w-6 h-6 mr-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6m-6 0v6m0 0H7m6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.tags.index') }}" class="flex items-center px-4 py-2 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-blue-100 dark:hover:bg-gray-700">
                <svg class="w-6 h-6 mr-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h10M7 12h10M7 17h10"/></svg>
                Tags
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 py-2 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-blue-100 dark:hover:bg-gray-700">
                <svg class="w-6 h-6 mr-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                Categories
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-blue-100 dark:hover:bg-gray-700">
                <svg class="w-6 h-6 mr-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 15c2.485 0 4.797.657 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Users
            </a>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center px-4 py-2 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-blue-100 dark:hover:bg-gray-700">
                <svg class="w-6 h-6 mr-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/></svg>
                Orders
            </a>
            <a href="{{ route('admin.food-items.index') }}" class="flex items-center px-4 py-2 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-blue-100 dark:hover:bg-gray-700">
                <svg class="w-6 h-6 mr-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2zm0 0v4m0-4h4"/></svg>
                Food Items
            </a>
            <a href="{{ route('admin.reports.orders') }}" class="flex items-center px-4 py-2 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-blue-100 dark:hover:bg-gray-700">
                <svg class="w-6 h-6 mr-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6"/></svg>
                Reports
            </a>
        </nav>
        <div class="p-4 border-t dark:border-gray-700">
            <a href="{{ route('logout') }}" class="flex items-center text-red-600 dark:text-red-400 hover:underline">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1"/></svg>
                Logout
            </a>
        </div>
    </aside>
    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-h-screen">
        <!-- Topbar -->
        <header class="bg-white dark:bg-gray-800 shadow flex items-center justify-between px-6 h-16">
            <!-- Search -->
            <div class="flex items-center w-1/2">
                <input type="text" placeholder="Search..." class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700">
            </div>
            <!-- Icons -->
            <div class="flex items-center space-x-6">
                <!-- Dark/Light Mode Toggle -->
                <button @click="toggle" class="focus:outline-none" :aria-label="dark ? 'Switch to light mode' : 'Switch to dark mode'">
                    <template x-if="!dark">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m8.66-13.66l-.71.71M4.05 19.95l-.71.71M21 12h-1M4 12H3m16.66 5.66l-.71-.71M4.05 4.05l-.71-.71M12 7a5 5 0 100 10 5 5 0 000-10z"/></svg>
                    </template>
                    <template x-if="dark">
                        <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z"/></svg>
                    </template>
                </button>
                <!-- Enhanced Notifications Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="relative focus:outline-none">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full text-xs px-1">3</span>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 shadow-lg rounded-lg z-50" x-cloak>
                        <div class="flex items-center justify-between p-4 border-b dark:border-gray-700 font-semibold">
                            <span>Notifications</span>
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
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8V8a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    <span class="absolute -top-1 -right-1 bg-blue-500 text-white rounded-full text-xs px-1">5</span>
                </button>
                <!-- Enhanced User Menu -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center focus:outline-none">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}" class="w-8 h-8 rounded-full mr-2" alt="Admin Avatar">
                        <span class="font-semibold text-gray-700 dark:text-gray-200">{{ Auth::user()->name ?? 'Admin' }}</span>
                        <svg class="w-4 h-4 ml-1 text-gray-600 dark:text-gray-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 shadow-lg rounded-lg z-50" x-cloak>
                        <div class="p-4 border-b dark:border-gray-700">
                            <div class="flex items-center space-x-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}" class="w-10 h-10 rounded-full" alt="Avatar">
                                <div>
                                    <div class="font-semibold text-gray-800 dark:text-gray-100">{{ Auth::user()->name ?? 'Admin' }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email ?? '' }}</div>
                                </div>
                            </div>
                        </div>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Profile</a>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Settings</a>
                        <div class="border-t dark:border-gray-700 my-2"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>
        <!-- Page Content -->
        <main class="flex-1 p-8 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
            @yield('content')
        </main>
    </div>
</div>
</body>
</html> 