@extends('layouts.app')

@section('content')
<div x-data="{ open: true }" class="flex min-h-screen bg-gray-100">
  <!-- Sidebar -->
  <aside :class="open ? 'w-64' : 'w-16'" class="transition-all duration-300 bg-gradient-to-b from-blue-600 to-blue-800 shadow-lg flex flex-col h-screen sticky top-0 z-30">
    <div class="flex items-center justify-between px-4 py-4 border-b border-blue-700">
      <span class="font-bold text-lg text-white" x-show="open">My Account</span>
      <button @click="open = !open" class="p-2 rounded hover:bg-blue-700 focus:outline-none">
        <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </div>
    <nav class="flex-1 py-4 space-y-2 overflow-y-auto">
      <!-- Dashboard -->
      <a href="{{ route('user.dashboard') }}" class="flex items-center px-4 py-2 text-white hover:bg-blue-700 transition rounded {{ request()->routeIs('user.dashboard') ? 'bg-blue-700' : '' }}">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6" />
        </svg>
        <span x-show="open" class="ml-3">Dashboard</span>
      </a>

      <!-- Orders Section -->
      <div x-show="open" class="px-4 pt-4">
        <h3 class="text-xs font-semibold text-blue-200 uppercase">Orders</h3>
        <div class="mt-2 space-y-1">
          <a href="{{ route('user.dashboard') }}?status=pending" class="flex items-center px-4 py-2 text-white hover:bg-blue-700 transition rounded">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="ml-3">Pending</span>
            @if(isset($pendingOrders) && $pendingOrders > 0)
            <span class="ml-auto bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">{{ $pendingOrders }}</span>
            @endif
          </a>
          <a href="{{ route('user.dashboard') }}?status=processing" class="flex items-center px-4 py-2 text-white hover:bg-blue-700 transition rounded">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            <span class="ml-3">Processing</span>
            @if(isset($processingOrders) && $processingOrders > 0)
            <span class="ml-auto bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">{{ $processingOrders }}</span>
            @endif
          </a>
          <a href="{{ route('user.dashboard') }}?status=shipped" class="flex items-center px-4 py-2 text-white hover:bg-blue-700 transition rounded">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
            </svg>
            <span class="ml-3">Shipped</span>
            @if(isset($shippedOrders) && $shippedOrders > 0)
            <span class="ml-auto bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">{{ $shippedOrders }}</span>
            @endif
          </a>
          <a href="{{ route('user.dashboard') }}?status=delivered" class="flex items-center px-4 py-2 text-white hover:bg-blue-700 transition rounded">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="ml-3">Delivered</span>
            @if(isset($deliveredOrders) && $deliveredOrders > 0)
            <span class="ml-auto bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">{{ $deliveredOrders }}</span>
            @endif
          </a>
        </div>
      </div>

      <!-- Account Section -->
      <div x-show="open" class="px-4 pt-4">
        <h3 class="text-xs font-semibold text-blue-200 uppercase">Account</h3>
        <div class="mt-2 space-y-1">
          <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-white hover:bg-blue-700 transition rounded">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="ml-3">Profile</span>
          </a>
          <a href="{{ route('wishlist.index') }}" class="flex items-center px-4 py-2 text-white hover:bg-blue-700 transition rounded">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <span class="ml-3">Wishlist</span>
          </a>
        </div>
      </div>

      <!-- Logout -->
      <form method="POST" action="{{ route('logout') }}" class="px-4 pt-4" x-show="open">
        @csrf
        <button type="submit" class="flex items-center w-full px-4 py-2 text-white hover:bg-red-600 transition rounded">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1" />
          </svg>
          <span class="ml-3">Logout</span>
        </button>
      </form>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 p-6">
    @yield('user-content')
  </main>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush

<style>
  .text-primary {
    color: #2563eb;
  }

  .bg-primary {
    background-color: #2563eb;
  }
</style>