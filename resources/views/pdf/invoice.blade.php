<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; color: #111; }
    h1,h2,h3,h4,h5 { margin: 0 0 8px 0; }
    .mb-2{margin-bottom:8px}.mb-3{margin-bottom:12px}.mb-4{margin-bottom:16px}
    .table{width:100%;border-collapse:collapse}
    .table th,.table td{border:1px solid #ddd;padding:6px;text-align:left}
    .text-right{text-align:right}
    .badge{display:inline-block;padding:2px 6px;border-radius:3px;background:#eee}
    .badge-success{background:#d1fae5}.badge-warning{background:#fef3c7}.badge-danger{background:#fee2e2}
    .badge-paid{background:#000;color:#fff;font-weight:bold}
    .badge-delivered{background:#000;color:#fff;font-weight:bold}
    .badge-refunded{background:#000;color:#fff;font-weight:bold}
    .header{border-bottom:2px solid #444;margin-bottom:16px;padding-bottom:8px}
  </style>
  <title>Invoice {{ $order->order_number }}</title>
  </head>
<body>
  <div class="header">
    <h2>Order Invoice</h2>
    <div>Order #: <strong>{{ $order->order_number }}</strong></div>
    <div>Date: {{ $order->created_at->format('Y-m-d H:i') }}</div>
    <div>Status:
      @php $status=$order->status; @endphp
      <span class="badge {{ $status==='delivered'?'badge-delivered':($status==='ordered'?'badge-warning':'badge-danger') }}">{{ ucfirst($status) }}</span>
    </div>
  </div>

  <h4 class="mb-2">Order Information</h4>
  <table class="table mb-4">
    <tbody>
      <tr><th>Payment Method</th><td>{{ ucfirst($order->payment_method) }}</td></tr>
      <tr><th>Payment Status</th><td>
        @php
          $paymentStatus = 'Pending';
          $statusClass = 'badge-warning';
          
          // Check the actual payment_status from database first
          if ($order->payment_status === 'paid') {
            $paymentStatus = 'Paid';
            $statusClass = 'badge-paid';
          } elseif ($order->payment_status === 'failed') {
            $paymentStatus = 'Failed';
            $statusClass = 'badge-danger';
          } elseif ($order->payment_status === 'refunded') {
            $paymentStatus = 'Refunded';
            $statusClass = 'badge-refunded';
          } elseif ($order->payment_status === 'pending') {
            $paymentStatus = 'Pending';
            $statusClass = 'badge-warning';
          } else {
            // Fallback logic only if payment_status is not set or unknown
            if ($order->status === 'delivered') {
              $paymentStatus = 'Paid';
              $statusClass = 'badge-paid';
            } elseif ($order->status === 'canceled') {
              $paymentStatus = 'Refunded';
              $statusClass = 'badge-refunded';
            }
          }
        @endphp
        <span class="badge {{ $statusClass }}">{{ $paymentStatus }}</span>
      </td></tr>
      <tr><th>Total Items</th><td>{{ $order->items->count() }}</td></tr>
    </tbody>
  </table>

  <h4 class="mb-2">Order Items</h4>
  <table class="table mb-4">
    <thead><tr><th>Product</th><th class="text-right">Price</th><th class="text-right">Qty</th><th class="text-right">Subtotal</th></tr></thead>
    <tbody>
      @foreach($order->items as $item)
      <tr>
        <td>{{ $item->product_name }}</td>
        <td class="text-right">${{ number_format($item->price,2) }}</td>
        <td class="text-right">{{ $item->quantity }}</td>
        <td class="text-right">${{ number_format($item->subtotal,2) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  @if($order->shippingAddress)
  <h4 class="mb-2">Shipping Address</h4>
  <table class="table mb-4">
    <tbody>
      <tr><th>Name</th><td>{{ $order->shippingAddress->name }}</td></tr>
      <tr><th>Address</th><td>{{ $order->shippingAddress->address ?? $order->shippingAddress->address_line1 }}</td></tr>
      <tr><th>City/State/Zip</th><td>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->zip }}</td></tr>
      <tr><th>Country</th><td>{{ $order->shippingAddress->country }}</td></tr>
      <tr><th>Phone</th><td>{{ $order->shippingAddress->phone }}</td></tr>
    </tbody>
  </table>
  @endif

  <h4 class="mb-2">Order Summary</h4>
  <table class="table">
    <tbody>
      <tr><th>Subtotal</th><td class="text-right">${{ number_format($order->subtotal,2) }}</td></tr>
      <tr><th>Tax</th><td class="text-right">${{ number_format($order->tax,2) }}</td></tr>
      <tr><th>Shipping</th><td class="text-right">${{ number_format($order->shipping,2) }}</td></tr>
      @if($order->discount>0)
      <tr><th>Discount</th><td class="text-right">-${{ number_format($order->discount,2) }}</td></tr>
      @endif
      <tr><th>Total</th><td class="text-right"><strong>${{ number_format($order->total,2) }}</strong></td></tr>
    </tbody>
  </table>

  @if($order->notes)
  <h4 class="mb-2">Notes</h4>
  <p>{{ $order->notes }}</p>
  @endif
</body>
</html>


