@extends('layouts.app')

@section('content')
<!-- Hero Section with Enhanced Animations -->
<div class="relative bg-gradient-to-br from-indigo-600 via-purple-500 to-pink-500 text-white overflow-hidden">
    <div class="container mx-auto px-6 py-32">
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 relative z-10">
                <h1 class="text-5xl md:text-6xl font-extrabold mb-6 animate-slide-in-left tracking-tight">
                    Elevate Your <span class="text-amber-300">Style</span>
                </h1>
                <p class="text-xl mb-8 opacity-90 leading-relaxed">Discover the perfect blend of fashion and innovation</p>
                <div class="flex space-x-4">
                    <a href="{{ route('products.index') }}"
                        class="bg-amber-300 text-indigo-800 px-8 py-4 rounded-full font-semibold hover:bg-amber-400 transform transition-all duration-300 hover:-translate-y-1 shadow-lg hover:shadow-2xl">
                        Shop Now →
                    </a>
                    <a href="#featured"
                        class="border-2 border-white text-white px-8 py-4 rounded-full font-semibold hover:bg-white/10 transition">
                        Learn More
                    </a>
                </div>
            </div>
            <div class="md:w-1/2 mt-12 md:mt-0">
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-400 to-pink-400 blur-3xl opacity-20"></div>
                    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c"
                        class="relative z-10 w-full rounded-2xl shadow-2xl animate-float"
                        alt="Fashion Hero Image">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Products Section -->
<section id="featured" class="py-24 bg-gradient-to-b from-gray-100 to-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-extrabold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-pink-500">
                Premium Picks
            </h2>
            <p class="text-gray-600 max-w-xl mx-auto text-lg">Explore our exclusive collection of top-tier products</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($products as $product)
            <div class="group relative bg-white rounded-3xl shadow-lg hover:shadow-xl transition-all duration-500">
                <div class="overflow-hidden rounded-t-3xl relative">
                    <img src="{{ $product->image ?? 'https://images.unsplash.com/photo-1523275335684-37898b6baf30' }}"
                        class="w-full h-72 object-cover transform group-hover:scale-110 transition-all duration-700"
                        alt="{{ $product->name }}">
                    <button class="absolute top-4 right-4 bg-white/80 backdrop-blur-md px-4 py-2 rounded-full text-sm font-medium hover:bg-white transition">
                        <i class="fas fa-heart text-pink-500 mr-2"></i> Save
                    </button>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-xl font-semibold mb-2">{{ $product->name }}</h3>
                            <p class="text-gray-500 text-sm">{{ $product->category }}</p>
                        </div>
                        <span class="bg-indigo-100 text-indigo-600 px-3 py-1 rounded-full text-xs font-medium">{{ ucfirst($product->status) }}</span>
                    </div>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-2xl font-bold text-indigo-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit"
                                class="flex items-center bg-indigo-600 text-white px-5 py-2 rounded-full font-medium hover:bg-indigo-700 transition">
                                <i class="fas fa-cart-plus mr-2"></i> Add
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Interactive Features Section -->
<div class="py-24 bg-gray-900 text-white">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="text-center p-8 bg-gray-800/50 rounded-3xl hover:bg-gray-800/70 transition-all duration-300">
                <div class="w-16 h-16 bg-indigo-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-rocket text-3xl text-indigo-400"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Express Delivery</h3>
                <p class="text-gray-300 text-sm">Get your order delivered in hours, not days</p>
            </div>
            <div class="text-center p-8 bg-gray-800/50 rounded-3xl hover:bg-gray-800/70 transition-all duration-300">
                <div class="w-16 h-16 bg-pink-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shield-alt text-3xl text-pink-400"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Trusted Quality</h3>
                <p class="text-gray-300 text-sm">Authentic products with full warranties</p>
            </div>
            <div class="text-center p-8 bg-gray-800/50 rounded-3xl hover:bg-gray-800/70 transition-all duration-300">
                <div class="w-16 h-16 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-headset text-3xl text-blue-400"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">24/7 Support</h3>
                <p class="text-gray-300 text-sm">Our team is here for you anytime</p>
            </div>
        </div>
    </div>
</div>

<!-- Interactive Product Showcase -->
<section class="py-24 bg-white">
    <div class="container mx-auto px-6">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="lg:w-1/2">
                <div class="grid grid-cols-2 gap-6">
                    <div class="relative h-96 bg-gray-100 rounded-3xl overflow-hidden hover:transform hover:scale-95 transition-all duration-500">
                        <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3"
                            class="absolute inset-0 w-full h-full object-cover">
                    </div>
                    <div class="relative h-96 bg-gray-100 rounded-3xl overflow-hidden hover:transform hover:scale-95 transition-all duration-500">
                        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e"
                            class="absolute inset-0 w-full h-full object-cover">
                    </div>
                </div>
            </div>
            <div class="lg:w-1/2">
                <h2 class="text-4xl font-extrabold mb-6">Innovative <span class="text-indigo-600">Tech</span> Awaits</h2>
                <p class="text-gray-600 mb-8 text-lg">Dive into the future with our cutting-edge gadgets and accessories.</p>
                <div class="space-y-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-microchip text-indigo-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-1">Advanced Tech</h4>
                            <p class="text-gray-600 text-sm">Next-gen processors for unmatched performance</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-battery-full text-indigo-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-1">Long-Lasting Power</h4>
                            <p class="text-gray-600 text-sm">Extended battery life for all-day use</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section with Glassmorphism -->
<div class="relative py-24 bg-[url('https://images.unsplash.com/photo-1557682250-33bd709cbe85')] bg-cover bg-center">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-md"></div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-4xl font-extrabold text-white mb-6">Stay Ahead with Us</h2>
            <p class="text-gray-200 mb-8 text-lg">Unlock exclusive deals, early access, and the latest updates.</p>
            <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                <input type="email"
                    class="flex-1 bg-white/10 text-white placeholder-gray-300 rounded-full px-6 py-3 border border-white/20 focus:outline-none focus:ring-2 focus:ring-amber-300"
                    placeholder="Enter your email">
                <button class="bg-indigo-600 text-white px-8 py-3 rounded-full font-semibold hover:bg-indigo-700 transition">
                    Join Now
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Interactive Contact Section -->
<section class="py-24 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="bg-white rounded-3xl shadow-xl p-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div class="space-y-8">
                    <h2 class="text-4xl font-extrabold mb-6">Get in Touch</h2>
                    <div class="flex items-center space-x-4">
                        <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-indigo-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">Our Office</h4>
                            <p class="text-gray-600">Tech Valley, Innovation District</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-phone text-indigo-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">Phone</h4>
                            <p class="text-gray-600">+62 123 456 789</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-envelope text-indigo-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">Email</h4>
                            <p class="text-gray-600">contact@tokoq.com</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-2xl p-8">
                    <form class="space-y-6">
                        <div>
                            <label class="block text-gray-700 mb-2 font-medium">Full Name</label>
                            <input type="text"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2 font-medium">Email Address</label>
                            <input type="email"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2 font-medium">Message</label>
                            <textarea rows="4"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                        </div>
                        <button class="w-full bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 transition">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .animate-slide-in-left {
        animation: slideInLeft 0.8s ease-out;
    }

    .animate-float {
        animation: float 5s ease-in-out infinite;
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-15px);
        }
    }
</style>
@endpush