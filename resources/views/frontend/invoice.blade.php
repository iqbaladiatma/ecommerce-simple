<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Invoice #{{ $order->id }}</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 14px;
      line-height: 1.6;
      color: #333;
    }

    .container {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
    }

    .header {
      text-align: center;
      margin-bottom: 30px;
    }

    .company-name {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .invoice-details {
      margin-bottom: 30px;
    }

    .customer-details {
      margin-bottom: 30px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 30px;
    }

    th,
    td {
      padding: 10px;
      border: 1px solid #ddd;
      text-align: left;
    }

    th {
      background-color: #f5f5f5;
    }

    .total {
      text-align: right;
      font-weight: bold;
    }

    .footer {
      margin-top: 50px;
      text-align: center;
      font-size: 12px;
      color: #666;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      <div class="company-name">Your Company Name</div>
      <div>123 Business Street, City, Country</div>
      <div>Phone: +1234567890 | Email: info@company.com</div>
    </div>

    <div class="invoice-details">
      <h2>Invoice #{{ $order->id }}</h2>
      <p>Date: {{ $order->created_at ? $order->created_at->format('d M Y') : 'N/A' }}</p>
      <p>Status: {{ ucfirst($order->status) }}</p>
      @if($transaction)
      <p>Payment Method: {{ ucfirst($transaction->payment_type) }}</p>
      <p>Transaction ID: {{ $transaction->transaction_id }}</p>
      @endif
    </div>

    <div class="customer-details">
      <h3>Bill To:</h3>
      <p><strong>{{ $customer->name }}</strong></p>
      <p>{{ $order->shipping_address }}</p>
      <p>{{ $order->shipping_city }}, {{ $order->shipping_postal_code }}</p>
      <p>Phone: {{ $order->shipping_phone }}</p>
      <p>Email: {{ $customer->email }}</p>
    </div>

    <table>
      <thead>
        <tr>
          <th>Item</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        @foreach($items as $item)
        <tr>
          <td>{{ $item->product->name }}</td>
          <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
          <td>{{ $item->quantity }}</td>
          <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3" class="total">Subtotal:</td>
          <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
          <td colspan="3" class="total">Shipping:</td>
          <td>Free</td>
        </tr>
        <tr>
          <td colspan="3" class="total">Total:</td>
          <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
        </tr>
      </tfoot>
    </table>

    <div class="footer">
      <p>Thank you for your business!</p>
      <p>This is a computer-generated invoice, no signature required.</p>
    </div>
  </div>
</body>

</html>