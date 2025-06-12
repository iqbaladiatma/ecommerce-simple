<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
  public function index()
  {
    $cacheKey = 'user_orders_' . Auth::id();

    $orders = cache()->remember($cacheKey, now()->addMinutes(30), function () {
      return Order::where('user_id', Auth::id())
        ->select(['id', 'order_number', 'total_amount', 'status', 'created_at'])
        ->with(['items.product:id,name,price,image'])
        ->latest()
        ->paginate(10);
    });

    return view('frontend.orders.index', compact('orders'));
  }

  public function show(Order $order)
  {
    try {
      // Log the attempt to access the order
      Log::info('Attempting to access order', [
        'order_id' => $order->id,
        'user_id' => Auth::id(),
        'order_user_id' => $order->user_id
      ]);

      // Ensure user can only view their own orders
      if ($order->user_id !== Auth::id()) {
        Log::warning('Unauthorized order access attempt', [
          'order_id' => $order->id,
          'user_id' => Auth::id(),
          'order_user_id' => $order->user_id
        ]);
        abort(403, 'You are not authorized to view this order.');
      }

      $cacheKey = 'order_details_' . $order->id;

      $order = cache()->remember($cacheKey, now()->addMinutes(30), function () use ($order) {
        return $order->load([
          'items.product:id,name,price,image',
          'transaction:id,order_id,amount,status',
          'user:id,name,email'
        ]);
      });

      // Ensure the order has all required attributes for route model binding
      if (!$order->exists) {
        Log::error('Order not found', [
          'order_id' => $order->id,
          'user_id' => Auth::id()
        ]);
        abort(404, 'Order not found.');
      }

      return view('frontend.orders.show', compact('order'));
    } catch (\Exception $e) {
      Log::error('Error accessing order', [
        'order_id' => $order->id ?? null,
        'user_id' => Auth::id(),
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ]);

      abort(404, 'Order not found or you are not authorized to view it.');
    }
  }
}
