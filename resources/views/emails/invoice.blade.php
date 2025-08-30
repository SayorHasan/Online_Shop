<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { border: 1px solid #ddd; padding: 6px; }
    .text-right { text-align: right; }
  </style>
  <title>Invoice {{ $order->order_number }}</title>
</head>
<body>
  <h2>Invoice #{{ $order->order_number }}</h2>
  <p>Status: <strong>{{ ucfirst($order->status) }}</strong></p>
  <p>Date: {{ $order->created_at->format('Y-m-d') }}</p>
  <h4>Bill To</h4>
  @if($order->shippingAddress)
    <p>
      {{ $order->shippingAddress->name }}<br>
      {{ $order->shippingAddress->address ?? $order->shippingAddress->address_line1 }}<br>
      {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->zip }}<br>
      {{ $order->shippingAddress->country }}
    </p>
  @endif

  <table class="table">
    <thead>
      <tr>
        <th>Product</th>
        <th class="text-right">Qty</th>
        <th class="text-right">Price</th>
        <th class="text-right">Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @foreach($order->items as $item)
      <tr>
        <td>{{ $item->product_name }}</td>
        <td class="text-right">{{ $item->quantity }}</td>
        <td class="text-right">${{ number_format($item->price,2) }}</td>
        <td class="text-right">${{ number_format($item->subtotal,2) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <p class="text-right">
    Subtotal: ${{ number_format($order->subtotal,2) }}<br>
    Tax: ${{ number_format($order->tax,2) }}<br>
    Shipping: ${{ number_format($order->shipping,2) }}<br>
    Discount: -${{ number_format($order->discount,2) }}<br>
    <strong>Total: ${{ number_format($order->total,2) }}</strong>
  </p>
</body>
</html>


