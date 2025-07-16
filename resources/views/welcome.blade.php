<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreshLocal - Your Neighborhood Food Marketplace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <style>
        .hero-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .food-gradient { background: linear-gradient(45deg, #ff6b6b, #feca57); }
        .cook-gradient { background: linear-gradient(45deg, #4ecdc4, #44a08d); }
        .community-gradient { background: linear-gradient(45deg, #a8edea, #fed6e3); }
        .float-animation { animation: float 3s ease-in-out infinite; }
        .fade-in { animation: fadeIn 1s ease-in-out; }
        .slide-up { animation: slideUp 0.8s ease-out forwards; opacity: 0; }
        .pulse-glow { animation: pulseGlow 2s ease-in-out infinite; }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(50px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes pulseGlow { 0%, 100% { box-shadow: 0 0 20px rgba(102, 126, 234, 0.4); } 50% { box-shadow: 0 0 30px rgba(102, 126, 234, 0.8); } }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .section-padding { padding: 4rem 1rem; }
        @media (max-width: 768px) { .section-padding { padding: 3rem 1rem; } }
    </style>
</head>
<body class="bg-white">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50" x-data="{ mobileOpen: false }">
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
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="#how-it-works" class="nav-link text-gray-600 hover:text-purple-600 px-3 py-2 text-sm font-medium transition-colors">How it Works</a>
                        <a href="#for-cooks" class="nav-link text-gray-600 hover:text-purple-600 px-3 py-2 text-sm font-medium transition-colors">For Cooks</a>
                        <a href="#stories" class="nav-link text-gray-600 hover:text-purple-600 px-3 py-2 text-sm font-medium transition-colors">Stories</a>
                        <a href="#help" class="nav-link text-gray-600 hover:text-purple-600 px-3 py-2 text-sm font-medium transition-colors">Help</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('search.index') }}" class="text-white bg-green-600 hover:bg-green-700 font-semibold px-4 py-2 rounded-lg flex items-center gap-2 shadow transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
                        Find Food
                    </a>
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-purple-600 font-medium">Login</a>
                    <a href="{{ route('register') }}" class="hero-gradient text-white px-4 py-2 rounded-lg font-medium hover:opacity-90 transition-opacity">Sign Up</a>
                    <a href="{{ route('register.provider') }}" class="border-2 border-purple-600 text-purple-600 px-4 py-2 rounded-lg font-medium hover:bg-purple-50 transition-colors ml-2">Provider Sign Up</a>
                    <!-- Hamburger for mobile -->
                    <button @click="mobileOpen = true" class="md:hidden ml-2 p-2 rounded focus:outline-none focus:ring-2 focus:ring-purple-400">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile Nav Overlay -->
        <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-full" class="fixed inset-0 bg-white bg-opacity-95 z-50 flex flex-col items-center justify-center space-y-8 text-xl font-semibold text-purple-700 md:hidden" @keydown.escape.window="mobileOpen = false" style="display: none;">
            <button @click="mobileOpen = false" class="absolute top-6 right-6 p-2 rounded focus:outline-none">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
            <a href="#how-it-works" class="nav-link block" @click="mobileOpen = false">How it Works</a>
            <a href="#for-cooks" class="nav-link block" @click="mobileOpen = false">For Cooks</a>
            <a href="#stories" class="nav-link block" @click="mobileOpen = false">Stories</a>
            <a href="#help" class="nav-link block" @click="mobileOpen = false">Help</a>
            <a href="{{ route('login') }}" class="block text-gray-600">Login</a>
            <a href="{{ route('register') }}" class="block text-purple-600">Sign Up</a>
            <a href="{{ route('register.provider') }}" class="block text-purple-600">Provider Sign Up</a>
        </div>
    </nav>
    <script>
// Smooth scroll and sticky highlight
const sectionIds = ['how-it-works', 'for-cooks', 'stories', 'help'];
function highlightNav() {
    let scrollPos = window.scrollY || window.pageYOffset;
    let found = false;
    for (let i = sectionIds.length - 1; i >= 0; i--) {
        const section = document.getElementById(sectionIds[i]);
        if (section && scrollPos + 80 >= section.offsetTop) {
            document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('text-purple-600', 'font-bold'));
            document.querySelectorAll('.nav-link').forEach(link => {
                if (link.getAttribute('href') === '#' + sectionIds[i]) {
                    link.classList.add('text-purple-600', 'font-bold');
                }
            });
            found = true;
            break;
        }
    }
    if (!found) {
        document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('text-purple-600', 'font-bold'));
    }
}
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
    // Sticky highlight on scroll
    window.addEventListener('scroll', highlightNav);
    highlightNav();
});
</script>
    <!-- Modern Home Page Body -->
    @include('home-modern')
</body>
</html>
