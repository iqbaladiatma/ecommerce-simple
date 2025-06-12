<div class="p-6">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Order Information -->
    <div class="bg-white rounded-lg shadow p-6">
      <h3 class="text-lg font-semibold mb-4">Order Information</h3>
      <div class="space-y-4">
        <div>
          <span class="text-gray-600">Order ID:</span>
          <span class="font-medium">{{ $order->id }}</span>
        </div>
        <div>
          <span class="text-gray-600">Status:</span>
          <span class="px-2 py-1 rounded-full text-sm
                        @if($order->status === 'paid') bg-green-100 text-green-800
                        @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
            {{ ucfirst($order->status) }}
          </span>
        </div>
        <div>
          <span class="text-gray-600">Total Amount:</span>
          <span class="font-medium">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
        </div>
        <div>
          <span class="text-gray-600">Order Date:</span>
          <span class="font-medium">{{ $order->created_at ? $order->created_at->format('d M Y H:i') : 'N/A' }}</span>
        </div>
      </div>
    </div>

    <!-- Customer Information -->
    <div class="bg-white rounded-lg shadow p-6">
      <h3 class="text-lg font-semibold mb-4">Customer Information</h3>
      <div class="space-y-4">
        <div>
          <span class="text-gray-600">Name:</span>
          <span class="font-medium">{{ $order->user->name }}</span>
        </div>
        <div>
          <span class="text-gray-600">Email:</span>
          <span class="font-medium">{{ $order->user->email }}</span>
        </div>
        <div>
          <span class="text-gray-600">Phone:</span>
          <span class="font-medium">{{ $order->shipping_phone }}</span>
        </div>
        <div>
          <span class="text-gray-600">Address:</span>
          <span class="font-medium">{{ $order->shipping_address }}</span>
        </div>
      </div>
    </div>

    <!-- Order Items -->
    <div class="md:col-span-2 bg-white rounded-lg shadow p-6">
      <h3 class="text-lg font-semibold mb-4">Order Items</h3>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach($order->items as $item)
            <tr>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <img class="h-10 w-10 rounded-full object-cover" src="{{ $item->product->image }}" alt="{{ $item->product->name }}">
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- Payment Information -->
    <div class="md:col-span-2 bg-white rounded-lg shadow p-6">
      <h3 class="text-lg font-semibold mb-4">Payment Information</h3>
      <div class="space-y-4">
        @if($order->transaction)
        <div>
          <span class="text-gray-600">Transaction ID:</span>
          <span class="font-medium">{{ $order->transaction->transaction_id }}</span>
        </div>
        <div>
          <span class="text-gray-600">Payment Method:</span>
          <span class="font-medium">{{ ucfirst($order->transaction->payment_type) }}</span>
        </div>
        <div>
          <span class="text-gray-600">Payment Status:</span>
          <span class="px-2 py-1 rounded-full text-sm
                        @if($order->transaction->status === 'settlement') bg-green-100 text-green-800
                        @elseif($order->transaction->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->transaction->status === 'expire') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
            {{ ucfirst($order->transaction->status) }}
          </span>
        </div>
        @else
        <div class="text-gray-500">No payment information available</div>
        @endif
      </div>
    </div>
  </div>
</div>