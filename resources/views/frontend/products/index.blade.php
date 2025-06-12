@extends('layouts.app')

@section('styles')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

  .product-card {
    transition: all 0.3s ease;
    overflow: hidden;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
  }

  .product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
  }

  .product-image {
    transition: transform 0.5s ease;
    height: 256px;
    object-fit: cover;
  }

  .product-card:hover .product-image {
    transform: scale(1.05);
  }

  .category-badge {
    position: absolute;
    top: 16px;
    left: 16px;
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    color: white;
    font-size: 12px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 20px;
    z-index: 10;
  }

  .wishlist-btn {
    transition: all 0.3s ease;
  }

  .wishlist-btn:hover {
    transform: scale(1.15);
  }

  .add-to-cart-btn {
    background: linear-gradient(135deg, #0ea5e9, #8b5cf6);
    transition: all 0.3s ease;
  }

  .add-to-cart-btn:hover {
    background: linear-gradient(135deg, #0284c7, #7c3aed);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(14, 165, 233, 0.3);
  }

  /* Pagination Styles */
  .pagination {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 2rem;
  }

  .pagination .page-item {
    list-style: none;
  }

  .pagination .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 2.5rem;
    height: 2.5rem;
    padding: 0 0.75rem;
    border-radius: 0.5rem;
    background: white;
    color: #4b5563;
    font-weight: 500;
    transition: all 0.2s;
    border: 1px solid #e5e7eb;
  }

  .pagination .page-link:hover {
    background: #f3f4f6;
    color: #1f2937;
  }

  .pagination .active .page-link {
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    color: white;
    border: none;
  }

  .pagination .disabled .page-link {
    background: #f3f4f6;
    color: #9ca3af;
    cursor: not-allowed;
  }
</style>
@endsection

@section('content')
<div class="py-12 bg-gradient-to-br from-gray-50 to-white">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl">
      <div class="p-8">
        <h2 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-8">Our Products</h2>

        {{-- Debug Info --}}
        @if(config('app.debug'))
        <div class="mb-4 p-4 bg-gray-100 rounded-lg">
          <p class="text-sm text-gray-600">Debug Info:</p>
          @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
          <p class="text-sm text-gray-600">Total Products: {{ $products->total() }}</p>
          <p class="text-sm text-gray-600">Current Page: {{ $products->currentPage() }}</p>
          <p class="text-sm text-gray-600">Products on this page: {{ $products->count() }}</p>
          @else
          <p class="text-sm text-gray-600">Products Count: {{ $products->count() }}</p>
          @endif
        </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
          @forelse($products as $product)
          <div class="group">
            <a href="{{ route('products.show', $product->id) }}" class="block">
              <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                <div class="relative">
                  <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                  @if($product->discount > 0)
                  <div class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-lg text-sm">
                    -{{ $product->discount }}%
                  </div>
                  @endif
                  <div class="absolute top-2 left-2">
                    <span class="bg-purple-100 text-purple-600 px-3 py-1 rounded-full text-sm font-medium">
                      {{ $product->category }}
                    </span>
                  </div>
                  <div class="absolute bottom-2 left-2">
                    <span class="bg-{{ $product->stock > 0 ? 'green' : 'red' }}-100 text-{{ $product->stock > 0 ? 'green' : 'red' }}-600 px-3 py-1 rounded-full text-sm font-medium">
                      {{ $product->stock > 0 ? 'In Stock: ' . $product->stock : 'Out of Stock' }}
                    </span>
                  </div>
                </div>
                <div class="p-4">
                  <h3 class="text-lg font-semibold text-gray-800 group-hover:text-purple-600 transition-colors">{{ $product->name }}</h3>
                  <div class="mt-2">
                    @if($product->discount > 0)
                    <div class="flex items-center gap-2">
                      <span class="text-red-500 font-bold">Rp {{ number_format($product->price - ($product->price * $product->discount / 100), 0, ',', '.') }}</span>
                      <span class="text-gray-400 line-through text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </div>
                    @else
                    <span class="text-purple-600 font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    @endif
                  </div>
                  <div class="mt-4 flex items-center justify-between">
                    <span class="text-sm text-gray-500">{{ ucfirst($product->status) }}</span>
                  </div>
                </div>
              </div>
            </a>
            <form action="{{ route('cart.add') }}" method="POST" class="mt-4">
              @csrf
              <input type="hidden" name="product_id" value="{{ $product->id }}">
              <input type="hidden" name="quantity" value="1">
              <button type="submit"
                class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white px-4 py-2 rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2 {{ $product->stock <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                {{ $product->stock <= 0 ? 'disabled' : '' }}>
                <i class="fas fa-shopping-cart"></i>
                <span>{{ $product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}</span>
              </button>
            </form>
          </div>
          @empty
          <div class="col-span-full text-center py-12">
            <i class="fas fa-box-open text-gray-300 text-6xl mb-4"></i>
            <p class="text-gray-500 text-lg">No products found</p>
          </div>
          @endforelse
        </div>

        @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->lastPage() > 1)
        <div class="mt-8">
          {{ $products->links() }}
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Wishlist Toggle (AJAX version)
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
          const icon = button.querySelector('i');
          if (data.status === 'added') {
            icon.classList.remove('text-gray-400');
            icon.classList.add('text-red-500');
            icon.classList.remove('far');
            icon.classList.add('fas');
          } else if (data.status === 'removed') {
            icon.classList.remove('text-red-500');
            icon.classList.add('text-gray-400');
            icon.classList.remove('fas');
            icon.classList.add('far');
          }
        });
    });
  });
</script>
@endpush