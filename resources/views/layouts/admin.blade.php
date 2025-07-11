<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Sarvana</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg flex flex-col">
            <div class="h-16 flex items-center justify-center border-b">
                <span class="text-xl font-bold text-orange-600">Sarvana Admin</span>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded hover:bg-orange-100 {{ request()->routeIs('admin.dashboard') ? 'bg-orange-50 font-bold text-orange-700' : 'text-gray-700' }}">Dashboard</a>
                
                <!-- Reports Section -->
                <div class="mt-6">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Reports</div>
                    <a href="{{ route('admin.reports.index') }}" class="block px-4 py-2 rounded hover:bg-orange-100 {{ request()->routeIs('admin.reports.*') ? 'bg-orange-50 font-bold text-orange-700' : 'text-gray-700' }}">Analytics</a>
                    <a href="{{ route('admin.reports.sales') }}" class="block px-4 py-2 rounded hover:bg-orange-100 {{ request()->routeIs('admin.reports.sales') ? 'bg-orange-50 font-bold text-orange-700' : 'text-gray-700' }} ml-4">Sales</a>
                    <a href="{{ route('admin.reports.users') }}" class="block px-4 py-2 rounded hover:bg-orange-100 {{ request()->routeIs('admin.reports.users') ? 'bg-orange-50 font-bold text-orange-700' : 'text-gray-700' }} ml-4">Users</a>
                    <a href="{{ route('admin.reports.providers') }}" class="block px-4 py-2 rounded hover:bg-orange-100 {{ request()->routeIs('admin.reports.providers') ? 'bg-orange-50 font-bold text-orange-700' : 'text-gray-700' }} ml-4">Providers</a>
                    <a href="{{ route('admin.reports.orders') }}" class="block px-4 py-2 rounded hover:bg-orange-100 {{ request()->routeIs('admin.reports.orders') ? 'bg-orange-50 font-bold text-orange-700' : 'text-gray-700' }} ml-4">Orders</a>
                </div>
                
                <!-- Management Section -->
                <div class="mt-6">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Management</div>
                    <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 rounded hover:bg-orange-100 {{ request()->routeIs('admin.users.*') ? 'bg-orange-50 font-bold text-orange-700' : 'text-gray-700' }}">Users</a>
                    <a href="{{ route('admin.orders.index') }}" class="block px-4 py-2 rounded hover:bg-orange-100 {{ request()->routeIs('admin.orders.*') ? 'bg-orange-50 font-bold text-orange-700' : 'text-gray-700' }}">Orders</a>
                    <a href="{{ route('admin.tags.index') }}" class="block px-4 py-2 rounded hover:bg-orange-100 {{ request()->routeIs('admin.tags.*') ? 'bg-orange-50 font-bold text-orange-700' : 'text-gray-700' }}">Tags</a>
                    <a href="{{ route('admin.categories.index') }}" class="block px-4 py-2 rounded hover:bg-orange-100 {{ request()->routeIs('admin.categories.*') ? 'bg-orange-50 font-bold text-orange-700' : 'text-gray-700' }}">Categories</a>
                </div>
            </nav>
            <div class="p-4 border-t">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 rounded bg-orange-50 hover:bg-orange-100 text-orange-700 font-semibold">Logout</button>
                </form>
            </div>
        </aside>
        <!-- Main Content -->
        <main class="flex-1 p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-2xl font-bold text-gray-800">@yield('title', 'Admin Dashboard')</h1>
                <div class="text-gray-600">{{ Auth::user()->name ?? '' }}</div>
            </div>
            @yield('content')
        </main>
    </div>
</body>
</html> 