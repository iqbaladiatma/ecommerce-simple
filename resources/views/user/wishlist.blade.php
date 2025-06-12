@extends('layouts.user')

@section('user-content')
<div class="space-y-6">
  <!-- Header -->
  <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-bold text-gray-800">My Wishlist</h2>
          <p class="text-gray-600 mt-1">Save your favorite products for later</p>
        </div>
        <div class="flex items-center gap-4">
          <div class="text-right">
            <p class="text-sm text-gray-600">Total Items</p>
            <p class="text-2xl font-bold text-primary">{{ $wishlistItems->count() }}</p>
          </div>
          <div class="h-12 w-12 rounded-full bg-primary/10 flex items-center justify-center">
            <i class="fas fa-heart text-primary text-xl"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Wishlist Items -->
  <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
      @if($wishlistItems->count() > 0)
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($wishlistItems as $item)
        <div class="bg-white border rounded-lg overflow-hidden hover:shadow-lg transition">
          <div class="relative">
            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-full h-48 object-cover">
            <form action="{{ route('wishlist.remove', $item->id) }}" method="POST" class="absolute top-2 right-2">
              @csrf
              @method('DELETE')
              <button type="submit" class="p-2 bg-white rounded-full shadow hover:bg-red-50 transition">
                <i class="fas fa-heart text-red-500"></i>
              </button>
            </form>
          </div>
          <div class="p-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $item->product->name }}</h3>
            <p class="text-gray-600 text-sm mb-4">{{ Str::limit($item->product->description, 100) }}</p>
            <div class="flex items-center justify-between">
              <span class="text-lg font-bold text-primary">Rp {{ number_format($item->product->price, 0, ',', '.') }}</span>
              <form action="{{ route('cart.add', $item->product->id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark transition">
                  Add to Cart
                </button>
              </form>
            </div>
          </div>
        </div>
        @endforeach
      </div>
      @else
      <div class="text-center py-12">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
          <i class="fas fa-heart text-gray-400 text-2xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Your wishlist is empty</h3>
        <p class="text-gray-500 mb-6">Start adding products to your wishlist</p>
        <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
          Browse Products
        </a>
      </div>
      @endif
    </div>
  </div>
</div>

<style>
  .text-primary {
    color: #2563eb;
  }

  .bg-primary {
    background-color: #2563eb;
  }

  .bg-primary-dark {
    background-color: #1d4ed8;
  }

  .bg-primary\/10 {
    background-color: rgba(37, 99, 235, 0.1);
  }
</style>
@endsection