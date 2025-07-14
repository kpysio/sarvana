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
    <nav class="bg-white shadow-sm sticky top-0 z-50">
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
                        <a href="#" class="text-gray-600 hover:text-purple-600 px-3 py-2 text-sm font-medium transition-colors">How it Works</a>
                        <a href="#" class="text-gray-600 hover:text-purple-600 px-3 py-2 text-sm font-medium transition-colors">For Cooks</a>
                        <a href="#" class="text-gray-600 hover:text-purple-600 px-3 py-2 text-sm font-medium transition-colors">Stories</a>
                        <a href="#" class="text-gray-600 hover:text-purple-600 px-3 py-2 text-sm font-medium transition-colors">Help</a>
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
    <!-- Modern Home Page Body -->
    @include('home-modern')
</body>
</html>
