@extends('layouts.app')

@section('content')
<div class="py-12 bg-gradient-to-br from-gray-50 to-white">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl">
      <div class="p-8">
        <div class="flex items-center justify-between mb-8">
          <h2 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Shopping Cart</h2>
          <a href="{{ route('products.index') }}" class="text-purple-600 hover:text-purple-700 flex items-center gap-2 transition-colors">
            <i class="fas fa-arrow-left"></i>
            <span>Continue Shopping</span>
          </a>
        </div>

        @if(empty($cart))
        <div class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-gray-200">
          <i class="fas fa-shopping-cart text-gray-300 text-6xl mb-4"></i>
          <p class="text-gray-500 text-lg mb-6">Your cart is empty</p>
          <a href="{{ route('products.index') }}" class="inline-block bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-4 rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
            Start Shopping
          </a>
        </div>
        @else
        <div class="space-y-8">
          <!-- Cart Items -->
          <div class="space-y-4">
            @foreach($cart as $item)
            <div class="flex items-center space-x-6 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300">
              <div class="w-28 h-28 flex-shrink-0">
                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover rounded-xl">
              </div>
              <div class="flex-1">
                <h3 class="font-semibold text-gray-800 text-xl mb-2">{{ $item['name'] }}</h3>
                <div class="space-y-2">
                  <p class="text-purple-600 font-medium text-lg">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                  <p class="text-sm text-gray-500">Subtotal: Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</p>
                </div>
              </div>
              <div class="flex items-center space-x-4">
                <form action="{{ route('cart.update', $item['id']) }}" method="POST" class="flex items-center border rounded-xl overflow-hidden">
                  @csrf
                  @method('PATCH')
                  <button type="button" onclick="updateQuantity({{ $item['id'] }}, 'decrease')"
                    class="px-4 py-2 text-gray-600 hover:text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-minus"></i>
                  </button>
                  <input type="number" name="quantity" id="quantity-{{ $item['id'] }}" value="{{ $item['quantity'] }}" min="1"
                    class="w-12 text-center border-x py-2 focus:outline-none focus:ring-0">
                  <button type="button" onclick="updateQuantity({{ $item['id'] }}, 'increase')"
                    class="px-4 py-2 text-gray-600 hover:text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-plus"></i>
                  </button>
                </form>
                <form action="{{ route('cart.remove', $item['id']) }}" method="POST" class="inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="text-red-500 hover:text-white hover:bg-red-500 border border-red-500 px-4 py-2 rounded-xl transition-all duration-300 flex items-center gap-2">
                    <i class="fas fa-trash"></i>
                    <span>Remove</span>
                  </button>
                </form>
              </div>
            </div>
            @endforeach
          </div>

          <!-- Order Summary -->
          <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
            <h3 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-8">Order Summary</h3>

            <!-- Shipping Information -->
            <div class="mb-8">
              <h4 class="text-xl font-semibold text-gray-700 mb-6">Shipping Information</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-2">Shipping Address</label>
                  <textarea name="shipping_address" id="shipping_address" rows="3" required
                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                    placeholder="Enter your complete shipping address"></textarea>
                </div>
                <div>
                  <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-2">Billing Address</label>
                  <textarea name="billing_address" id="billing_address" rows="3" required
                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                    placeholder="Enter your billing address"></textarea>
                </div>
                <div>
                  <label for="shipping_city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                  <input type="text" name="shipping_city" id="shipping_city" required
                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                    placeholder="Enter your city">
                </div>
                <div>
                  <label for="shipping_postal_code" class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                  <input type="text" name="shipping_postal_code" id="shipping_postal_code" required
                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                    placeholder="Enter your postal code">
                </div>
                <div>
                  <label for="shipping_phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                  <input type="tel" name="shipping_phone" id="shipping_phone" required
                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                    placeholder="Enter your phone number">
                </div>
                <div>
                  <label for="shipping_method" class="block text-sm font-medium text-gray-700 mb-2">Shipping Method</label>
                  <select name="shipping_method" id="shipping_method" required
                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    <option value="regular">Regular Shipping (3-5 days) - Free</option>
                    <option value="express">Express Shipping (1-2 days) - Rp 50.000</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Order Details -->
            <div class="border-t border-gray-200 pt-6">
              <h4 class="text-xl font-semibold text-gray-700 mb-6">Order Details</h4>
              <div class="space-y-4">
                <div class="flex justify-between">
                  <span class="text-gray-600">Subtotal</span>
                  <span class="font-semibold text-gray-800">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Shipping</span>
                  <span class="font-semibold text-gray-800" id="shipping-cost">Free</span>
                </div>
                <div class="border-t pt-4">
                  <div class="flex justify-between">
                    <span class="text-xl font-bold text-gray-800">Total</span>
                    <span class="text-xl font-bold text-purple-600" id="total-amount">Rp {{ number_format($total, 0, ',', '.') }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Additional Notes -->
            <div class="mt-8">
              <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Order Notes (Optional)</label>
              <textarea name="notes" id="notes" rows="2"
                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                placeholder="Add any special instructions or notes"></textarea>
            </div>

            <!-- Payment Button -->
            <form action="{{ route('midtrans.pay') }}" method="POST" class="mt-8">
              @csrf
              <input type="hidden" name="shipping_address" id="shipping_address_input">
              <input type="hidden" name="billing_address" id="billing_address_input">
              <input type="hidden" name="shipping_method" id="shipping_method_input">
              <input type="hidden" name="shipping_cost" id="shipping_cost_input" value="0">
              <input type="hidden" name="notes" id="notes_input">
              <button type="submit"
                class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-4 rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-3 text-lg font-semibold transform hover:-translate-y-0.5">
                <img src="https://cdn.midtrans.com/images/midtrans-logo.png" alt="Midtrans" class="h-6">
                <span>Proceed to Payment</span>
              </button>
            </form>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
  function updateQuantity(productId, action) {
    const input = document.getElementById(`quantity-${productId}`);
    let currentQuantity = parseInt(input.value);

    if (action === 'increase') {
      currentQuantity++;
    } else if (action === 'decrease' && currentQuantity > 1) {
      currentQuantity--;
    }

    // Update input value
    input.value = currentQuantity;

    // Submit the form
    const form = input.closest('form');
    const formData = new FormData(form);
    formData.append('quantity', currentQuantity);

    fetch(form.action, {
        method: 'PATCH',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          quantity: currentQuantity
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Reload the page to update totals
          window.location.reload();
        }
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }

  // Handle shipping method change
  document.getElementById('shipping_method').addEventListener('change', function() {
    const shippingCost = this.value === 'express' ? 50000 : 0;
    const subtotal = {
      {
        $total
      }
    };
    const total = subtotal + shippingCost;

    document.getElementById('shipping-cost').textContent = shippingCost === 0 ? 'Free' : `Rp ${shippingCost.toLocaleString('id-ID')}`;
    document.getElementById('total-amount').textContent = `Rp ${total.toLocaleString('id-ID')}`;
    document.getElementById('shipping_cost_input').value = shippingCost;
  });

  // Handle form submission
  document.querySelector('form[action="{{ route("midtrans.pay") }}"]').addEventListener('submit', function(e) {
    e.preventDefault();

    // Get values from form fields
    const shippingAddress = document.getElementById('shipping_address').value;
    const billingAddress = document.getElementById('billing_address').value;
    const shippingMethod = document.getElementById('shipping_method').value;
    const notes = document.getElementById('notes').value;

    // Validate required fields
    if (!shippingAddress || !billingAddress) {
      alert('Please fill in all required fields');
      return;
    }

    // Set values to hidden inputs
    document.getElementById('shipping_address_input').value = shippingAddress;
    document.getElementById('billing_address_input').value = billingAddress;
    document.getElementById('shipping_method_input').value = shippingMethod;
    document.getElementById('notes_input').value = notes;

    // Submit form via AJAX
    fetch(this.action, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          shipping_address: shippingAddress,
          billing_address: billingAddress,
          shipping_method: shippingMethod,
          shipping_cost: document.getElementById('shipping_cost_input').value,
          notes: notes
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.snap_token) {
          window.snap.pay(data.snap_token, {
            onSuccess: function(result) {
              window.location.href = '{{ route("midtrans.finish") }}';
            },
            onPending: function(result) {
              window.location.href = '{{ route("midtrans.unfinish") }}';
            },
            onError: function(result) {
              window.location.href = '{{ route("midtrans.error") }}';
            },
            onClose: function() {
              // Handle when customer closes the popup without finishing the payment
              alert('Anda menutup popup tanpa menyelesaikan pembayaran');
            }
          });
        } else {
          alert('Gagal memproses pembayaran: ' + (data.error || 'Unknown error'));
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Gagal memproses pembayaran. Silakan coba lagi.');
      });
  });
</script>
@endpush
@endsection