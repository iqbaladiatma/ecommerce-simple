@extends('layouts.app')

@section('content')
<div class="py-12">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
      <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <!-- Product Image -->
          <div class="relative">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-96 object-cover rounded-lg">
            <div class="absolute top-4 right-4">
              <form action="{{ route('wishlist.toggle', ['product' => $product->id]) }}" method="POST" class="wishlist-form">
                @csrf
                <button type="submit" class="bg-white/90 backdrop-blur-sm p-3 rounded-full hover:bg-red-100 transition">
                  <i class="fas fa-heart text-red-500"></i>
                </button>
              </form>
            </div>
          </div>

          <!-- Product Info -->
          <div class="space-y-6">
            <div>
              <h1 class="text-3xl font-bold mb-2">{{ $product->name }}</h1>
              <p class="text-gray-500">{{ $product->category }}</p>
            </div>

            <div class="text-3xl font-bold text-purple-600">
              Rp {{ number_format($product->price, 0, ',', '.') }}
            </div>

            <div class="space-y-4">
              <p class="text-gray-600">{{ $product->description }}</p>

              <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-500">Stock:</span>
                <span class="font-semibold">{{ $product->stock }} units</span>
              </div>

              @if($product->stock > 0)
              <form action="{{ route('cart.add') }}" method="POST" class="flex items-center space-x-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="flex items-center border rounded-lg">
                  <button type="button" class="px-4 py-2 text-gray-600 hover:text-gray-700 focus:outline-none" onclick="decrementQuantity()">-</button>
                  <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-16 text-center border-0 focus:ring-0">
                  <button type="button" class="px-4 py-2 text-gray-600 hover:text-gray-700 focus:outline-none" onclick="incrementQuantity()">+</button>
                </div>
                <button type="submit" class="flex-1 bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition">
                  <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
                </button>
              </form>
              @else
              <div class="bg-red-100 text-red-600 px-4 py-2 rounded-lg">
                Out of Stock
              </div>
              @endif
            </div>
          </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->isNotEmpty())
        <div class="mt-16">
          <h2 class="text-2xl font-bold mb-6">Related Products</h2>
          <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @foreach($relatedProducts as $relatedProduct)
            <div class="bg-white rounded-lg shadow-md overflow-hidden group">
              <a href="{{ route('products.show', $relatedProduct) }}">
                <img src="{{ $relatedProduct->image }}" alt="{{ $relatedProduct->name }}" class="w-full h-48 object-cover">
                <div class="p-4">
                  <h3 class="font-semibold text-lg mb-2">{{ $relatedProduct->name }}</h3>
                  <p class="text-gray-600 text-sm mb-4">{{ $relatedProduct->category }}</p>
                  <div class="flex justify-between items-center">
                    <span class="text-xl font-bold text-purple-600">
                      Rp {{ number_format($relatedProduct->price, 0, ',', '.') }}
                    </span>
                  </div>
                </div>
              </a>
            </div>
            @endforeach
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  function incrementQuantity() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.getAttribute('max'));
    const currentValue = parseInt(input.value);
    if (currentValue < max) {
      input.value = currentValue + 1;
    }
  }

  function decrementQuantity() {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    if (currentValue > 1) {
      input.value = currentValue - 1;
    }
  }

  // Wishlist Toggle
  document.querySelectorAll('.wishlist-form').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      fetch(this.action, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
          const button = this.querySelector('button');
          if (data.status === 'added') {
            button.classList.add('bg-red-100');
          } else {
            button.classList.remove('bg-red-100');
          }
        });
    });
  });
</script>
@endpush
@endsection