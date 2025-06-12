<?php

namespace App\Services;

use Midtrans\Snap;
use Midtrans\Config;
use Exception;
use Illuminate\Support\Facades\Log;

class MidtransService
{
  public function __construct()
  {
    Config::$serverKey = config('services.midtrans.server_key');
    Config::$isProduction = config('services.midtrans.is_production');
    Config::$isSanitized = true;
    Config::$is3ds = true;
  }

  public function createTransaction($params)
  {
    try {
      return Snap::createTransaction($params);
    } catch (Exception $e) {
      Log::error('Midtrans transaction creation failed', [
        'error' => $e->getMessage(),
        'params' => $params
      ]);
      throw new Exception('Payment gateway error: ' . $e->getMessage());
    }
  }

  public function getTransactionStatus($orderId)
  {
    try {
      return \Midtrans\Transaction::status($orderId);
    } catch (Exception $e) {
      Log::error('Failed to get transaction status', [
        'error' => $e->getMessage(),
        'order_id' => $orderId
      ]);
      throw new Exception('Failed to get payment status: ' . $e->getMessage());
    }
  }

  public function cancelTransaction($orderId)
  {
    try {
      return \Midtrans\Transaction::cancel($orderId);
    } catch (Exception $e) {
      Log::error('Failed to cancel transaction', [
        'error' => $e->getMessage(),
        'order_id' => $orderId
      ]);
      throw new Exception('Failed to cancel payment: ' . $e->getMessage());
    }
  }

  public function expireTransaction($orderId)
  {
    try {
      return \Midtrans\Transaction::expire($orderId);
    } catch (Exception $e) {
      Log::error('Failed to expire transaction', [
        'error' => $e->getMessage(),
        'order_id' => $orderId
      ]);
      throw new Exception('Failed to expire payment: ' . $e->getMessage());
    }
  }
}
