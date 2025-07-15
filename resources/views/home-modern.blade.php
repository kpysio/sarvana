<!-- Hero Section -->
<section class="hero-gradient text-white section-padding relative overflow-hidden" id="hero">
    <div class="max-w-7xl mx-auto relative z-10">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="fade-in">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                    Fresh, Homemade Food from Your 
                    <span class="text-yellow-300">Neighbors</span>
                </h1>
                <p class="text-xl mb-8 text-purple-100 leading-relaxed">
                    Discover delicious homemade meals, support local cooks, and enjoy fresh food made with love right in your neighborhood.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('search.index') }}" class="bg-white text-purple-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-colors pulse-glow flex items-center justify-center">
                        <i class="fas fa-search mr-2"></i>
                        Find Food Near Me
                    </a>
                    <a href="{{ route('register.provider') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-purple-600 transition-colors flex items-center justify-center">
                        <i class="fas fa-chef-hat mr-2"></i>
                        Start Cooking
                    </a>
                </div>
            </div>
            <div class="relative">
                <div class="float-animation">
                    <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=500&h=400&fit=crop" 
                         alt="Homemade Food" 
                         class="rounded-2xl shadow-2xl">
                </div>
                <div class="absolute -top-4 -right-4 bg-yellow-400 text-purple-800 px-4 py-2 rounded-full font-bold">
                    <i class="fas fa-star mr-1"></i>
                    4.9 Rating
                </div>
                <div class="absolute -bottom-4 -left-4 bg-green-400 text-white px-4 py-2 rounded-full font-bold">
                    <i class="fas fa-users mr-1"></i>
                    500+ Cooks
                </div>
            </div>
        </div>
    </div>
    <!-- Background decorations -->
    <div class="absolute top-20 right-20 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 left-20 w-24 h-24 bg-yellow-300/20 rounded-full blur-2xl"></div>
</section>
<!-- How It Works Section -->
<section class="section-padding bg-gray-50" id="how-it-works">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">How It Works</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Simple steps to enjoy fresh, homemade food from your local community
            </p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center card-hover bg-white p-8 rounded-2xl shadow-sm">
                <div class="w-16 h-16 hero-gradient rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-map-marker-alt text-white text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-4">1. Find Local Cooks</h3>
                <p class="text-gray-600 leading-relaxed">
                    Browse delicious homemade food from verified cooks in your neighborhood. See ratings, reviews, and photos.
                </p>
            </div>
            <div class="text-center card-hover bg-white p-8 rounded-2xl shadow-sm">
                <div class="w-16 h-16 food-gradient rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shopping-cart text-white text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-4">2. Order Fresh Food</h3>
                <p class="text-gray-600 leading-relaxed">
                    Place your order with special instructions. Choose from daily specials, weekly meals, or custom orders.
                </p>
            </div>
            <div class="text-center card-hover bg-white p-8 rounded-2xl shadow-sm">
                <div class="w-16 h-16 cook-gradient rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-heart text-white text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-4">3. Enjoy & Support</h3>
                <p class="text-gray-600 leading-relaxed">
                    Collect your fresh meal and enjoy! Rate your experience and support local home cooks in your community.
                </p>
            </div>
        </div>
    </div>
</section>
<!-- Stories Section -->
<section class="section-padding" id="stories">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Community Stories</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Real stories from our amazing community of food lovers and home cooks
            </p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="card-hover bg-white p-6 rounded-2xl shadow-sm border">
                <div class="flex items-center mb-4">
                    <img src="https://images.unsplash.com/photo-1494790108755-2616c-photo?w=50&h=50&fit=crop&crop=face" 
                         alt="Sarah" class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <h4 class="font-semibold text-gray-800">Sarah M.</h4>
                        <p class="text-sm text-gray-600">Home Cook</p>
                    </div>
                </div>
                <p class="text-gray-700 mb-4">
                    "I started making extra portions for my neighbors and now I'm earning £200+ weekly! It's amazing to share my love for cooking."
                </p>
                <div class="flex text-yellow-400">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>
            <div class="card-hover bg-white p-6 rounded-2xl shadow-sm border">
                <div class="flex items-center mb-4">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=50&h=50&fit=crop&crop=face" 
                         alt="Mike" class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <h4 class="font-semibold text-gray-800">Mike J.</h4>
                        <p class="text-sm text-gray-600">Student</p>
                    </div>
                </div>
                <p class="text-gray-700 mb-4">
                    "As a student, finding affordable, healthy meals was tough. Now I get amazing home-cooked food from Mrs. Chen daily!"
                </p>
                <div class="flex text-yellow-400">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>
            <div class="card-hover bg-white p-6 rounded-2xl shadow-sm border">
                <div class="flex items-center mb-4">
                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=50&h=50&fit=crop&crop=face" 
                         alt="Emma" class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <h4 class="font-semibold text-gray-800">Emma W.</h4>
                        <p class="text-sm text-gray-600">Working Mom</p>
                    </div>
                </div>
                <p class="text-gray-700 mb-4">
                    "With two kids and a busy job, FreshLocal saves me hours. The food is incredible and my kids love it!"
                </p>
                <div class="flex text-yellow-400">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Help Community Section -->
<section class="section-padding community-gradient" id="help">
    <div class="max-w-7xl mx-auto">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6">
                    Help Your Community Thrive
                </h2>
                <p class="text-xl text-gray-700 mb-8 leading-relaxed">
                    Every order supports local families, creates connections, and builds a stronger neighborhood. Join thousands making a difference.
                </p>
                <div class="grid grid-cols-2 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600 mb-2">500+</div>
                        <p class="text-gray-600">Active Cooks</p>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600 mb-2">10K+</div>
                        <p class="text-gray-600">Happy Customers</p>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600 mb-2">£50K+</div>
                        <p class="text-gray-600">Earned by Cooks</p>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600 mb-2">25+</div>
                        <p class="text-gray-600">Neighborhoods</p>
                    </div>
                </div>
            </div>
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=500&h=400&fit=crop" 
                     alt="Community" 
                     class="rounded-2xl shadow-2xl">
                <div class="absolute inset-0 bg-gradient-to-t from-purple-900/20 to-transparent rounded-2xl"></div>
            </div>
        </div>
    </div>
</section>
<!-- Help House Cooks Section -->
<section class="section-padding bg-gray-50" id="for-cooks">
    <div class="max-w-7xl mx-auto">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="order-2 md:order-1">
                <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=500&h=400&fit=crop" 
                     alt="Home Cook" 
                     class="rounded-2xl shadow-2xl">
            </div>
            <div class="order-1 md:order-2">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6">
                    Turn Your Kitchen Into a Business
                </h2>
                <p class="text-xl text-gray-700 mb-8 leading-relaxed">
                    Love cooking? Share your passion and earn money from your home kitchen. We provide the platform, you provide the delicious food.
                </p>
                <div class="space-y-4 mb-8">
                    <div class="flex items-start">
                        <div class="w-8 h-8 cook-gradient rounded-full flex items-center justify-center mr-4 mt-1">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Flexible Schedule</h4>
                            <p class="text-gray-600">Cook when you want, set your own hours</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-8 h-8 cook-gradient rounded-full flex items-center justify-center mr-4 mt-1">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Extra Income</h4>
                            <p class="text-gray-600">Earn £100-500+ weekly from your kitchen</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-8 h-8 cook-gradient rounded-full flex items-center justify-center mr-4 mt-1">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Community Impact</h4>
                            <p class="text-gray-600">Help neighbors with fresh, homemade meals</p>
                        </div>
                    </div>
                </div>
                <button onclick="window.location.href='{{ route('register.provider') }}'" class="cook-gradient text-white px-8 py-4 rounded-lg font-semibold text-lg hover:opacity-90 transition-opacity">
                    <i class="fas fa-chef-hat mr-2"></i>
                    Start Cooking Today
                </button>
            </div>
        </div>
    </div>
</section>
<!-- Fresh Food Section -->
<section class="section-padding">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Always Fresh, Always Local</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Experience the difference of truly fresh, homemade food made with love and local ingredients
            </p>
        </div>
        <div class="grid md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-leaf text-green-600 text-xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Fresh Ingredients</h3>
                <p class="text-gray-600 text-sm">Sourced locally, prepared daily</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-clock text-blue-600 text-xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Made to Order</h3>
                <p class="text-gray-600 text-sm">Cooked fresh when you order</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-heart text-purple-600 text-xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Made with Love</h3>
                <p class="text-gray-600 text-sm">Home cooks who care about quality</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-home text-yellow-600 text-xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Local Community</h3>
                <p class="text-gray-600 text-sm">Supporting your neighborhood</p>
            </div>
        </div>
    </div>
</section>
<!-- Verified Accounts Section -->
<section class="section-padding bg-gray-50">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Trusted & Verified</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                All our cooks are verified for your safety and peace of mind
            </p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center card-hover bg-white p-8 rounded-2xl shadow-sm">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shield-alt text-green-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Identity Verified</h3>
                <p class="text-gray-600 leading-relaxed">
                    All cooks complete identity verification and background checks for your safety.
                </p>
            </div>
            <div class="text-center card-hover bg-white p-8 rounded-2xl shadow-sm">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-certificate text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Food Safety</h3>
                <p class="text-gray-600 leading-relaxed">
                    Kitchen hygiene standards and food safety guidelines are strictly maintained.
                </p>
            </div>
            <div class="text-center card-hover bg-white p-8 rounded-2xl shadow-sm">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-star text-purple-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Rated & Reviewed</h3>
                <p class="text-gray-600 leading-relaxed">
                    Community ratings and reviews help you choose the best cooks in your area.
                </p>
            </div>
        </div>
    </div>
</section>
<!-- CTA Section -->
<section class="section-padding hero-gradient text-white">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
            Ready to Taste the Difference?
        </h2>
        <p class="text-xl mb-8 text-purple-100">
            Join thousands discovering amazing homemade food in their neighborhood
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button class="bg-white text-purple-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-colors">
                <i class="fas fa-mobile-alt mr-2"></i>
                Download App
            </button>
            <button class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-purple-600 transition-colors">
                <i class="fas fa-utensils mr-2"></i>
                Browse Food
            </button>
        </div>
    </div>
</section>
<!-- Footer -->
<footer class="bg-gray-800 text-white section-padding">
    <div class="max-w-7xl mx-auto">
        <div class="grid md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 hero-gradient rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-utensils text-white"></i>
                    </div>
                    <span class="text-xl font-bold">FreshLocal</span>
                </div>
                <p class="text-gray-400 mb-4">
                    Connecting communities through fresh, homemade food.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-facebook text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-twitter text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                </div>
            </div>
            <div>
                <h4 class="font-semibold mb-4">For Customers</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#how-it-works" class="hover:text-white transition-colors">How it Works</a></li>
                    <li><a href="#hero" class="hover:text-white transition-colors">Find Food</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Safety</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Help Center</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">For Cooks</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#for-cooks" class="hover:text-white transition-colors">Start Cooking</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Cook Resources</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Guidelines</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Support</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Company</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-white transition-colors">About Us</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Careers</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Press</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Contact</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; {{ date('Y') }} FreshLocal. All rights reserved.</p>
        </div>
    </div>
</footer> 