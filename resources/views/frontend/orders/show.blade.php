@extends('layouts.app')

@section('content')
<div class="py-12">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
      <div class="p-6">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold">Order Details</h2>
          <a href="{{ route('orders.index') }}" class="text-purple-600 hover:text-purple-900">
            <i class="fas fa-arrow-left mr-2"></i> Back to Orders
          </a>
        </div>

        <!-- Order Summary -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <h3 class="text-lg font-semibold mb-4">Order Information</h3>
              <div class="space-y-2">
                <p><span class="font-medium">Order Number:</span> {{ $order->order_number }}</p>
                <p><span class="font-medium">Date:</span> {{ $order->created_at ? $order->created_at->format('M d, Y H:i') : 'N/A' }}</p>
                <p><span class="font-medium">Status:</span>
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($order->status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                    {{ ucfirst($order->status) }}
                  </span>
                </p>
                <p><span class="font-medium">Payment Status:</span>
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($order->payment_status === 'paid') bg-green-100 text-green-800
                                        @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                    {{ ucfirst($order->payment_status) }}
                  </span>
                </p>
              </div>
            </div>
            <div>
              <h3 class="text-lg font-semibold mb-4">Shipping Information</h3>
              <div class="space-y-2">
                <p><span class="font-medium">Shipping Address:</span></p>
                <p class="text-gray-600">{{ $order->shipping_address }}</p>
                <p><span class="font-medium">Shipping Method:</span> {{ $order->shipping_method ?? 'Standard Shipping' }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Order Items -->
        <div class="mb-6">
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
                  <td class="px-6 py-4">
                    <div class="flex items-center">
                      <div class="h-16 w-16 flex-shrink-0">
                        <img class="h-16 w-16 rounded-lg object-cover" src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}">
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
                    <div class="text-sm font-medium text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        <!-- Order Total -->
        <div class="bg-gray-50 rounded-lg p-6">
          <div class="flex justify-between items-center">
            <div>
              <h3 class="text-lg font-semibold">Order Total</h3>
              <p class="text-sm text-gray-500">Including shipping and taxes</p>
            </div>
            <div class="text-right">
              <p class="text-sm text-gray-500">Shipping Cost</p>
              <p class="text-lg font-bold">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</p>
              <p class="text-sm text-gray-500 mt-2">Total Amount</p>
              <p class="text-2xl font-bold text-purple-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
            </div>
          </div>
        </div>

        @if($order->status === 'pending' && $order->payment_status === 'pending')
        <div class="mt-6 flex justify-end">
          <a href="{{ route('order.print', $order) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
            <i class="fas fa-print mr-2"></i> Print Invoice
          </a>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection