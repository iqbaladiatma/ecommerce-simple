@extends('layouts.app')

@section('content')
<div class="py-12">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
      <div class="p-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Our Products</h1>
            <p class="mt-2 text-gray-600">Discover our latest collection</p>
          </div>
          <div class="mt-4 md:mt-0">
            <form action="{{ route('products.index') }}" method="GET" class="flex gap-4">
              <select name="category" class="rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                <option value="">All Categories</option>
                <option value="Electronics" {{ request('category') == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                <option value="Fashion" {{ request('category') == 'Fashion' ? 'selected' : '' }}>Fashion</option>
                <option value="Home" {{ request('category') == 'Home' ? 'selected' : '' }}>Home</option>
                <option value="Beauty" {{ request('category') == 'Beauty' ? 'selected' : '' }}>Beauty</option>
              </select>
              <select name="sort" class="rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
              </select>
              <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                Filter
              </button>
            </form>
          </div>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          @forelse($products as $product)
          <div class="group relative bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300">
            <div class="overflow-hidden rounded-t-2xl relative">
              <img src="{{ $product->image_url }}"
                class="w-full h-64 object-cover transform group-hover:scale-105 transition-all duration-500"
                alt="{{ $product->name }}">
              @auth
              <form action="{{ route('wishlist.toggle', ['product' => $product->id]) }}" method="POST" class="absolute top-4 right-4 wishlist-form">
                @csrf
                <button type="submit" class="bg-white/90 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-semibold hover:bg-white transition">
                  <i class="{{ auth()->user()->wishlistProducts->contains($product->id) ? 'fas text-red-500' : 'far text-gray-400' }} fa-heart mr-2"></i> Wishlist
                </button>
              </form>
              @endauth
            </div>
            <div class="p-6">
              <div class="flex justify-between items-start">
                <div>
                  <h3 class="text-lg font-bold mb-1">{{ $product->name }}</h3>
                  <p class="text-gray-500 text-sm">{{ $product->category }}</p>
                </div>
                <span class="bg-purple-100 text-purple-600 px-3 py-1 rounded-full text-sm">{{ ucfirst($product->status) }}</span>
              </div>
              <div class="mt-4 flex justify-between items-center">
                <span class="text-2xl font-bold text-purple-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                <form action="{{ route('cart.add') }}" method="POST">
                  @csrf
                  <input type="hidden" name="product_id" value="{{ $product->id }}">
                  <input type="hidden" name="quantity" value="1">
                  <button type="submit" class="flex items-center bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition">
                    <i class="fas fa-shopping-cart mr-2"></i> Add
                  </button>
                </form>
              </div>
            </div>
          </div>
          @empty
          <div class="col-span-full text-center py-12">
            <i class="fas fa-box-open text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500">No products found</p>
          </div>
          @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
          {{ $products->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection