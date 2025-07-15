<aside class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg z-30 flex flex-col hidden lg:flex">
    <div class="h-16 flex items-center px-6 font-bold text-xl border-b">MyStore</div>
    <nav class="flex-1 px-4 py-6 space-y-2">
        <a href="{{ route('provider.dashboard') }}" class="flex items-center px-3 py-2 rounded hover:bg-gray-100">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6m-6 0v6m0 0H7m6 0h6" /></svg>
            Dashboard
        </a>
        <a href="{{ route('provider.food-items.index') }}" class="flex items-center px-3 py-2 rounded hover:bg-gray-100">
            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
            My Store
        </a>
        <a href="{{ route('provider.orders.index') }}" class="flex items-center px-3 py-2 rounded hover:bg-gray-100">
            <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6" /></svg>
            Orders
        </a>
    </nav>
</aside>
<!-- Mobile sidebar toggle (optional, for future) --> 