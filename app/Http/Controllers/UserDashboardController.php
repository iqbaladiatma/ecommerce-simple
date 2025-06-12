<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserDashboardController extends Controller
{
  public function index(Request $request)
  {
    $user = Auth::user();
    $status = $request->query('status');

    // Log user information
    Log::info('User Dashboard Access', [
      'user_id' => $user->id,
      'user_email' => $user->email,
      'user_name' => $user->name
    ]);

    // Base query for user's orders
    $ordersQuery = Order::where('user_id', $user->id);

    // Get all order counts in a single query using conditional aggregation
    $orderCounts = $ordersQuery->selectRaw('
        COUNT(*) as total_orders,
        SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_orders,
        SUM(CASE WHEN status = "processing" THEN 1 ELSE 0 END) as processing_orders,
        SUM(CASE WHEN status = "shipped" THEN 1 ELSE 0 END) as shipped_orders,
        SUM(CASE WHEN status = "delivered" THEN 1 ELSE 0 END) as delivered_orders,
        SUM(CASE WHEN status = "paid" THEN 1 ELSE 0 END) as paid_orders,
        SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_orders,
        SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled_orders
    ')->first();

    // Extract counts from the result
    $totalOrders = $orderCounts->total_orders;
    $pendingOrders = $orderCounts->pending_orders;
    $processingOrders = $orderCounts->processing_orders;
    $shippedOrders = $orderCounts->shipped_orders;
    $deliveredOrders = $orderCounts->delivered_orders;
    $paidOrders = $orderCounts->paid_orders;
    $completedOrders = $orderCounts->completed_orders;
    $cancelledOrders = $orderCounts->cancelled_orders;

    // Log order counts
    Log::info('Order Counts', [
      'total' => $totalOrders,
      'pending' => $pendingOrders,
      'processing' => $processingOrders,
      'shipped' => $shippedOrders,
      'delivered' => $deliveredOrders,
      'paid' => $paidOrders,
      'completed' => $completedOrders,
      'cancelled' => $cancelledOrders
    ]);

    // Get recent orders with status filter and eager loading
    $recentOrders = $ordersQuery->when($status, function ($query) use ($status) {
      return $query->where('status', $status);
    })
      ->select(['id', 'order_number', 'status', 'total_amount', 'created_at', 'user_id']) // Explicitly select required fields
      ->with(['items.product:id,name,price,image', 'user:id,name,email']) // Optimize eager loading by selecting only needed fields
      ->latest()
      ->take(5)
      ->get();

    // Log recent orders
    Log::info('Recent Orders', [
      'count' => $recentOrders->count(),
      'orders' => $recentOrders->map(function ($order) {
        return [
          'id' => $order->id,
          'order_number' => $order->order_number,
          'status' => $order->status,
          'total_amount' => $order->total_amount,
          'created_at' => $order->created_at
        ];
      })->toArray()
    ]);

    // Debug information
    if ($recentOrders->isEmpty()) {
      // Check if there are any orders at all
      $allOrders = Order::where('user_id', $user->id)->get();
      if ($allOrders->isEmpty()) {
        // No orders found for this user
        Log::info('No orders found for user: ' . $user->id);
      } else {
        // Orders exist but might be filtered out
        Log::info('Orders exist but filtered out', [
          'status' => $status,
          'available_statuses' => $allOrders->pluck('status')->unique()->values()->toArray(),
          'total_orders' => $allOrders->count(),
          'order_ids' => $allOrders->pluck('id')->toArray()
        ]);
      }
    }

    return view('user.dashboard', compact(
      'totalOrders',
      'pendingOrders',
      'processingOrders',
      'shippedOrders',
      'deliveredOrders',
      'paidOrders',
      'completedOrders',
      'cancelledOrders',
      'recentOrders',
      'status'
    ));
  }
}
