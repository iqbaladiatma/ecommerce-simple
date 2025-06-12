@extends('layouts.app')

@section('content')
<div class="py-12">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
      <div class="p-6 text-gray-900">
        <h2 class="text-2xl font-bold mb-6">My Wishlist</h2>

        @if($wishlists->isEmpty())
        <div class="text-center py-12">
          <i class="fas fa-heart text-gray-300 text-5xl mb-4"></i>
          <p class="text-gray-500">Your wishlist is empty</p>
          <a href="{{ route('products.index') }}" class="mt-4 inline-block bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition">
            Browse Products
          </a>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
          @foreach($wishlists as $product)
          <div class="bg-white rounded-lg shadow-md overflow-hidden group">
            <div class="relative">
              <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
              <div class="absolute top-2 right-2">
                <form action="{{ route('wishlist.remove', $product) }}" method="POST" class="inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="bg-white/90 backdrop-blur-sm p-2 rounded-full hover:bg-red-100 transition">
                    <i class="fas fa-heart text-red-500"></i>
                  </button>
                </form>
              </div>
            </div>
            <div class="p-4">
              <h3 class="font-semibold text-lg mb-2">{{ $product->name }}</h3>
              <p class="text-gray-600 text-sm mb-4">{{ $product->category }}</p>
              <div class="flex justify-between items-center">
                <span class="text-xl font-bold text-purple-600">
                  Rp {{ number_format($product->price, 0, ',', '.') }}
                </span>
                <form action="{{ route('cart.add') }}" method="POST">
                  @csrf
                  <input type="hidden" name="product_id" value="{{ $product->id }}">
                  <button type="submit" class="bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition">
                    <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
                  </button>
                </form>
              </div>
            </div>
          </div>
          @endforeach
        </div>

        <div class="mt-6">
          {{ $wishlists->links() }}
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection