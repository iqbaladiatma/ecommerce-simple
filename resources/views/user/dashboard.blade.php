@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100">
  <!-- Sidebar -->
  <div class="w-64 bg-white shadow-sm">
    <div class="p-4 border-b">
      <h2 class="text-lg font-semibold text-gray-800">My Account</h2>
    </div>
    <nav class="p-4">
      <div class="space-y-2">
        <a href="{{ route('user.dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 bg-primary/10 rounded-lg">
          <i class="fas fa-tachometer-alt w-5"></i>
          <span class="ml-3">Dashboard</span>
        </a>
        <div class="pt-4">
          <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase">Orders</h3>
          <div class="mt-2 space-y-1">
            <a href="{{ route('user.dashboard') }}?status=pending" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
              <i class="fas fa-clock w-5 text-yellow-500"></i>
              <span class="ml-3">Pending</span>
              @if($pendingOrders > 0)
              <span class="ml-auto bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">{{ $pendingOrders }}</span>
              @endif
            </a>
            <a href="{{ route('user.dashboard') }}?status=processing" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
              <i class="fas fa-cog w-5 text-blue-500"></i>
              <span class="ml-3">Processing</span>
              @if($processingOrders > 0)
              <span class="ml-auto bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">{{ $processingOrders }}</span>
              @endif
            </a>
            <a href="{{ route('user.dashboard') }}?status=shipped" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
              <i class="fas fa-truck w-5 text-purple-500"></i>
              <span class="ml-3">Shipped</span>
              @if($shippedOrders > 0)
              <span class="ml-auto bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">{{ $shippedOrders }}</span>
              @endif
            </a>
            <a href="{{ route('user.dashboard') }}?status=delivered" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
              <i class="fas fa-check-circle w-5 text-green-500"></i>
              <span class="ml-3">Delivered</span>
              @if($deliveredOrders > 0)
              <span class="ml-auto bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">{{ $deliveredOrders }}</span>
              @endif
            </a>
          </div>
        </div>
        <div class="pt-4">
          <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase">Account</h3>
          <div class="mt-2 space-y-1">
            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
              <i class="fas fa-user w-5"></i>
              <span class="ml-3">Profile</span>
            </a>
            <a href="{{ route('wishlist.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
              <i class="fas fa-heart w-5"></i>
              <span class="ml-3">Wishlist</span>
            </a>
          </div>
        </div>
      </div>
    </nav>
  </div>

  <!-- Main Content -->
  <div class="flex-1 p-8">
    <!-- Welcome Section -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
      <div class="p-6">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-2xl font-bold text-gray-800">Welcome back, {{ auth()->user()->name }}!</h2>
            <p class="text-gray-600 mt-1">Track your orders and manage your account</p>
          </div>
          <div class="flex items-center gap-4">
            <div class="text-right">
              <p class="text-sm text-gray-600">Total Orders</p>
              <p class="text-2xl font-bold text-primary">{{ $totalOrders }}</p>
            </div>
            <div class="h-12 w-12 rounded-full bg-primary/10 flex items-center justify-center">
              <i class="fas fa-shopping-bag text-primary text-xl"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Order Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
      <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600">Pending</p>
            <p class="text-2xl font-bold text-yellow-500">{{ $pendingOrders }}</p>
          </div>
          <div class="h-12 w-12 rounded-full bg-yellow-100 flex items-center justify-center">
            <i class="fas fa-clock text-yellow-500 text-xl"></i>
          </div>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600">Processing</p>
            <p class="text-2xl font-bold text-blue-500">{{ $processingOrders }}</p>
          </div>
          <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
            <i class="fas fa-cog text-blue-500 text-xl"></i>
          </div>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600">Shipped</p>
            <p class="text-2xl font-bold text-purple-500">{{ $shippedOrders }}</p>
          </div>
          <div class="h-12 w-12 rounded-full bg-purple-100 flex items-center justify-center">
            <i class="fas fa-truck text-purple-500 text-xl"></i>
          </div>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600">Delivered</p>
            <p class="text-2xl font-bold text-green-500">{{ $deliveredOrders }}</p>
          </div>
          <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
            <i class="fas fa-check-circle text-green-500 text-xl"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Additional Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
      <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600">Paid</p>
            <p class="text-2xl font-bold text-indigo-500">{{ $paidOrders }}</p>
          </div>
          <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
            <i class="fas fa-credit-card text-indigo-500 text-xl"></i>
          </div>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600">Completed</p>
            <p class="text-2xl font-bold text-teal-500">{{ $completedOrders }}</p>
          </div>
          <div class="h-12 w-12 rounded-full bg-teal-100 flex items-center justify-center">
            <i class="fas fa-check-double text-teal-500 text-xl"></i>
          </div>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-600">Cancelled</p>
            <p class="text-2xl font-bold text-red-500">{{ $cancelledOrders }}</p>
          </div>
          <div class="h-12 w-12 rounded-full bg-red-100 flex items-center justify-center">
            <i class="fas fa-times-circle text-red-500 text-xl"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-semibold text-gray-800">Recent Orders</h3>
          <a href="{{ route('orders.index') }}" class="text-primary hover:text-primary-dark text-sm font-medium">
            View All Orders
          </a>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @forelse($recentOrders as $order)
              <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">#{{ $order->order_number }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ $order->created_at ? $order->created_at->format('M d, Y') : 'N/A' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                @elseif($order->status === 'paid') bg-indigo-100 text-indigo-800
                                @elseif($order->status === 'completed') bg-teal-100 text-teal-800
                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                    {{ ucfirst($order->status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <a href="{{ route('orders.show', $order) }}" class="text-primary hover:text-primary-dark">View Details</a>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                  <div class="flex flex-col items-center justify-center py-8">
                    <i class="fas fa-shopping-bag text-gray-300 text-4xl mb-4"></i>
                    <p class="text-gray-500">No orders found</p>
                    <a href="{{ route('products.index') }}" class="mt-4 inline-block bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-dark transition">
                      Start Shopping
                    </a>
                  </div>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  :root {
    --primary: #2563eb;
    --primary-dark: #1d4ed8;
  }

  .text-primary {
    color: var(--primary);
  }

  .text-primary-dark {
    color: var(--primary-dark);
  }

  .bg-primary {
    background-color: var(--primary);
  }

  .bg-primary\/10 {
    background-color: rgba(37, 99, 235, 0.1);
  }
</style>
@endsection