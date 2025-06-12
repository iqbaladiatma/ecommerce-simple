@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4">
    <h1 class="text-3xl font-bold mb-6">Keranjang Belanja</h1>

    @if (session('cart') && count(session('cart')) > 0)
    <form id="checkout-form" action="{{ route('midtrans.pay') }}" method="POST" class="space-y-6">
        @csrf
        <div class="space-y-4">
            @php $total = 0; @endphp
            @foreach (session('cart') as $item)
            @php
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
            @endphp
            <div class="bg-white p-4 rounded shadow flex justify-between items-center">
                <div>
                    <h2 class="font-semibold">{{ $item['name'] }}</h2>
                    <p class="text-sm text-gray-600">Rp {{ number_format($item['price'], 0, ',', '.') }} x {{ $item['quantity'] }}</p>
                </div>
                <p class="font-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
            </div>
            @endforeach
        </div>

        <div class="bg-white p-6 rounded shadow space-y-4">
            <h3 class="text-xl font-bold mb-4">Informasi Pengiriman</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Alamat Pengiriman</label>
                    <textarea name="shipping_address" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Alamat Penagihan</label>
                    <textarea name="billing_address" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"></textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Metode Pengiriman</label>
                    <select name="shipping_method" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="regular">Regular (2-3 hari) - Rp 15.000</option>
                        <option value="express">Express (1 hari) - Rp 30.000</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Catatan</label>
                    <textarea name="notes" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"></textarea>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold">Ringkasan Pembayaran</h3>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Subtotal</p>
                    <p class="text-lg font-bold">Rp {{ number_format($total, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="flex justify-between items-center mb-4">
                <p class="text-gray-600">Biaya Pengiriman</p>
                <p class="font-medium" id="shipping-cost">Rp 15.000</p>
            </div>

            <div class="border-t pt-4">
                <div class="flex justify-between items-center">
                    <h4 class="text-lg font-bold">Total</h4>
                    <p class="text-xl font-bold" id="total-amount">Rp {{ number_format($total + 15000, 0, ',', '.') }}</p>
                </div>
            </div>

            <button type="submit" class="mt-6 w-full bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-colors">
                Bayar Sekarang
            </button>
        </div>
    </form>
    @else
    <div class="text-center py-12">
        <i class="fas fa-shopping-cart text-gray-300 text-5xl mb-4"></i>
        <p class="text-gray-600 mb-4">Keranjangmu kosong.</p>
        <a href="{{ route('products.index') }}" class="inline-block bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-colors">
            Mulai Belanja
        </a>
    </div>
    @endif
</div>

<script>
    const subtotal = {
        {
            $total
        }
    };
    const shippingMethodSelect = document.querySelector('select[name="shipping_method"]');
    const shippingCostElement = document.getElementById('shipping-cost');
    const totalAmountElement = document.getElementById('total-amount');

    function updateTotals() {
        const shippingCost = shippingMethodSelect.value === 'regular' ? 15000 : 30000;
        const total = subtotal + shippingCost;

        shippingCostElement.textContent = `Rp ${shippingCost.toLocaleString('id-ID')}`;
        totalAmountElement.textContent = `Rp ${total.toLocaleString('id-ID')}`;
    }

    shippingMethodSelect.addEventListener('change', updateTotals);

    // Initialize totals
    updateTotals();
</script>
@endsection