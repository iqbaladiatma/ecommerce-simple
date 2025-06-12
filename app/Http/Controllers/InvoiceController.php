<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
  public function print(Order $order)
  {
    $pdf = PDF::loadView('frontend.invoice', [
      'order' => $order,
      'items' => $order->items,
      'customer' => $order->user,
      'transaction' => $order->transaction,
    ]);

    return $pdf->stream('invoice-' . $order->id . '.pdf');
  }
}
