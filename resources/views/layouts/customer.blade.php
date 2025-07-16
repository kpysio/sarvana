<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard - @yield('title', 'Sarvana')</title>
    @vite('resources/css/app.css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">
<div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-lg flex flex-col">
        <div class="h-20 flex items-center justify-center border-b">
            <span class="text-2xl font-bold text-blue-700">Sarvana Customer</span>
        </div>
        <nav class="flex-1 py-6 px-4 space-y-2">
            <a href="{{ route('customer.dashboard') }}" class="flex items-center px-4 py-2 rounded-lg text-gray-700 hover:bg-blue-100">
                <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6m-6 0v6m0 0H7m6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('customers.orders.index') }}" class="flex items-center px-4 py-2 rounded-lg text-gray-700 hover:bg-blue-100">
                <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6"/></svg>
                My Orders
            </a>
        </nav>
        <div class="p-4 border-t">
            <a href="{{ route('logout') }}" class="flex items-center text-red-600 hover:underline">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1"/></svg>
                Logout
            </a>
        </div>
    </aside>
    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-h-screen">
        <!-- Topbar -->
        <header class="bg-white shadow flex items-center justify-between px-6 h-16">
            <div class="flex items-center w-1/2">
                <input type="text" placeholder="Search..." class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div class="flex items-center space-x-6">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center focus:outline-none">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Customer') }}" class="w-8 h-8 rounded-full mr-2" alt="Customer Avatar">
                        <span class="font-semibold text-gray-700">{{ Auth::user()->name ?? 'Customer' }}</span>
                        <svg class="w-4 h-4 ml-1 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-56 bg-white shadow-lg rounded-lg z-50" x-cloak>
                        <div class="p-4 border-b">
                            <div class="flex items-center space-x-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Customer') }}" class="w-10 h-10 rounded-full" alt="Avatar">
                                <div>
                                    <div class="font-semibold text-gray-800">{{ Auth::user()->name ?? 'Customer' }}</div>
                                    <div class="text-xs text-gray-500">{{ Auth::user()->email ?? '' }}</div>
                                </div>
                            </div>
                        </div>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Profile</a>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Settings</a>
                        <div class="border-t my-2"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>
        <!-- Page Content -->
        <main class="flex-1 p-8 bg-gray-100 text-gray-900">
            @yield('content')
        </main>
    </div>
</div>
</body>
</html> 