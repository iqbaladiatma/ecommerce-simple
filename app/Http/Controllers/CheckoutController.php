<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use App\Services\MidtransService;
use App\Services\CartService;
use App\Notifications\PaymentStatusNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class CheckoutController extends Controller
{
    protected $cartService;
    protected $midtransService;

    public function __construct(CartService $cartService, MidtransService $midtransService)
    {
        $this->cartService = $cartService;
        $this->midtransService = $midtransService;
    }

    public function checkout(Request $request)
    {
        try {
            $request->validate([
                'items' => 'required|array',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1'
            ]);

            DB::beginTransaction();

            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'total_amount' => 0,
                'status' => 'pending',
                'payment_status' => 'pending',
                'shipping_address' => $request->shipping_address,
                'billing_address' => $request->billing_address,
                'shipping_method' => $request->shipping_method,
                'shipping_cost' => $request->shipping_cost,
                'notes' => $request->notes
            ]);

            $total = $this->cartService->total();
            $order->update(['total_amount' => $total]);

            $params = [
                'transaction_details' => [
                    'order_id' => 'ORDER-' . $order->id . '-' . time(),
                    'gross_amount' => $total,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ]
            ];

            $snapToken = $this->midtransService->createTransaction($params);

            DB::commit();

            return response()->json([
                'snap_token' => $snapToken
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Checkout failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            return response()->json(['error' => 'Checkout failed: ' . $e->getMessage()], 500);
        }
    }

    public function midtransPay(Request $request)
    {
        try {
            $request->validate([
                'shipping_address' => 'required|string',
                'billing_address' => 'required|string',
                'shipping_method' => 'required|string',
                'shipping_cost' => 'required|numeric',
                'notes' => 'nullable|string'
            ]);

            $cart = $this->cartService->get();
            if (empty($cart)) {
                return response()->json(['error' => 'Keranjang belanja kosong'], 400);
            }

            // Validate stock availability
            foreach ($cart as $item) {
                $product = Product::find($item['id']);
                if (!$product) {
                    return response()->json(['error' => 'Produk tidak ditemukan'], 404);
                }
                if ($product->stock < $item['quantity']) {
                    return response()->json(['error' => "Stok produk {$product->name} tidak mencukupi"], 400);
                }
            }

            DB::beginTransaction();

            try {
                // Create order
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'order_number' => 'ORD-' . strtoupper(uniqid()),
                    'total_amount' => $this->cartService->total() + $request->shipping_cost,
                    'status' => 'pending',
                    'payment_status' => 'pending',
                    'shipping_address' => $request->shipping_address,
                    'billing_address' => $request->billing_address,
                    'shipping_method' => $request->shipping_method,
                    'shipping_cost' => $request->shipping_cost,
                    'notes' => $request->notes
                ]);

                // Create order items and update stock
                foreach ($cart as $item) {
                    $product = Product::find($item['id']);
                    $subtotal = $item['price'] * $item['quantity'];

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $subtotal
                    ]);

                    // Update product stock
                    $product->decrement('stock', $item['quantity']);
                }

                // Prepare Midtrans parameters
                $items = collect($cart)->map(function ($item) {
                    return [
                        'id' => $item['id'],
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'name' => $item['name'],
                    ];
                })->toArray();

                // Add shipping cost as an item
                $items[] = [
                    'id' => 'shipping',
                    'price' => $request->shipping_cost,
                    'quantity' => 1,
                    'name' => 'Biaya Pengiriman - ' . ucfirst($request->shipping_method),
                ];

                $params = [
                    'transaction_details' => [
                        'order_id' => 'ORDER-' . $order->id,
                        'gross_amount' => $order->total_amount,
                    ],
                    'item_details' => $items,
                    'customer_details' => [
                        'first_name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                        'phone' => Auth::user()->phone ?? '',
                        'billing_address' => [
                            'address' => $request->billing_address,
                        ],
                        'shipping_address' => [
                            'address' => $request->shipping_address,
                        ],
                    ],
                    'expiry' => [
                        'start_time' => now()->format('Y-m-d H:i:s O'),
                        'unit' => 'day',
                        'duration' => 1,
                    ],
                ];

                $snap = $this->midtransService->createTransaction($params);

                // Clear cart after successful order creation
                $this->cartService->clear();

                DB::commit();

                // Return Snap token
                return response()->json([
                    'snap_token' => $snap->token
                ]);
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (Exception $e) {
            Log::error('Payment initiation failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            return response()->json(['error' => 'Gagal memproses pembayaran: ' . $e->getMessage()], 500);
        }
    }

    public function callback(Request $request)
    {
        try {
            $payload = $request->all();
            $orderId = explode('-', $payload['order_id'])[1];
            $transactionStatus = $payload['transaction_status'];
            $fraudStatus = $payload['fraud_status'];

            $order = Order::find($orderId);
            if (!$order) {
                throw new Exception('Order not found');
            }

            DB::beginTransaction();

            $newStatus = $this->determineOrderStatus($transactionStatus, $fraudStatus);
            $order->status = $newStatus;
            $order->save();

            Transaction::create([
                'order_id' => $order->id,
                'transaction_id' => $payload['transaction_id'],
                'payment_type' => $payload['payment_type'],
                'status' => $transactionStatus,
                'amount' => $payload['gross_amount'],
                'raw_response' => json_encode($payload)
            ]);

            // Send notification
            $order->user->notify(new PaymentStatusNotification($order, $newStatus));

            DB::commit();

            return response()->json(['message' => 'Callback processed successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Callback processing failed', [
                'error' => $e->getMessage(),
                'payload' => $request->all()
            ]);
            return response()->json(['error' => 'Callback processing failed: ' . $e->getMessage()], 500);
        }
    }

    protected function determineOrderStatus($transactionStatus, $fraudStatus)
    {
        if ($transactionStatus == 'capture') {
            return $fraudStatus == 'challenge' ? 'challenge' : 'paid';
        }

        $statusMap = [
            'settlement' => 'paid',
            'cancel' => 'cancelled',
            'deny' => 'cancelled',
            'expire' => 'expired',
            'pending' => 'pending'
        ];

        return $statusMap[$transactionStatus] ?? 'pending';
    }

    public function finish(Request $request)
    {
        return redirect()->route('user.dashboard')
            ->with('success', 'Terima kasih atas pembelian Anda! Kami akan memberitahu Anda setelah pembayaran dikonfirmasi.');
    }

    public function unfinish(Request $request)
    {
        return redirect()->route('cart.index')
            ->with('warning', 'Pembayaran belum selesai. Anda dapat mencoba lagi dari riwayat pesanan Anda.');
    }

    public function error(Request $request)
    {
        return redirect()->route('cart.index')
            ->with('error', 'Pembayaran gagal. Silakan coba lagi atau hubungi tim dukungan kami untuk bantuan.');
    }

    public function cart()
    {
        return view('cart.cart');
    }

    public function add(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $request->quantity
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.cart')->with('success', 'Produk ditambahkan ke keranjang!');
    }
}
