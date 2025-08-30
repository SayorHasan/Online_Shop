<!doctype html>
<html>
<head><meta charset="utf-8"><title>Order Status</title></head>
<body>
  <p>Hi {{ $order->user->name }},</p>
  <p>Your order <strong>#{{ $order->order_number }}</strong> status is now <strong>{{ ucfirst($order->status) }}</strong>.</p>
  <p>Total: ${{ number_format($order->total,2) }}</p>
  <p>Thank you for shopping with us.</p>
</body>
</html>


