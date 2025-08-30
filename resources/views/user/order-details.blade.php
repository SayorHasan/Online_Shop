@extends('layouts.app')
@section('content')
<style>
    .order-details {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .order-details h5 {
        color: #333;
        border-bottom: 2px solid #6a6e51;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    .table-transaction th {
        background-color: #f8f9fa !important;
        font-weight: 600;
    }
    /* Ensure back button is entirely clickable and above overlays */
    .btn-back-orders{display:inline-flex;align-items:center;gap:.5rem;background:#000;color:#fff;border:1px solid #000;padding:.6rem 1rem;border-radius:.375rem;text-decoration:none;position:relative;z-index:10;pointer-events:auto}
    .btn-back-orders:hover{color:#fff;background:#111}
</style>
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
        <div class="row">
            <div class="col-lg-2">
                @include('user.account-nav')
            </div>
            <div class="col-lg-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="page-title">Order Details</h2>
                    <div class="d-flex gap-2">
                        <a href="{{ route('user.order.invoice', $order->id) }}" class="btn-back-orders" role="button" aria-label="Download Invoice">
                            <i class="fa fa-file-pdf-o"></i><span>Download Invoice</span>
                        </a>
                        <a href="{{ route('user.orders') }}" class="btn-back-orders" role="button" aria-label="Back to Orders">
                            <i class="fa fa-arrow-left"></i><span>Back to Orders</span>
                        </a>
                    </div>
                </div>

                <div class="order-details p-4">
                    <h5>Order Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Order Number:</strong></td>
                                    <td>{{ $order->order_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Order Date:</strong></td>
                                    <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($order->status == 'ordered')
                                            <span class="badge bg-warning">Ordered</span>
                                        @elseif($order->status == 'delivered')
                                            <span class="badge" style="background: #000; color: #fff; font-weight: bold;">Delivered</span>
                                        @elseif($order->status == 'canceled')
                                            <span class="badge bg-danger">Canceled</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Payment Method:</strong></td>
                                    <td>{{ ucfirst($order->payment_method) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Status:</strong></td>
                                    <td>
                                        @if($order->payment_status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($order->payment_status == 'paid')
                                            <span class="badge" style="background: #000; color: #fff; font-weight: bold;">Paid</span>
                                        @elseif($order->payment_status == 'failed')
                                            <span class="badge bg-danger">Failed</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($order->payment_status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Total Items:</strong></td>
                                    <td>{{ $order->items->count() }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="order-details p-4">
                    <h5>Order Items</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset('uploads/products/thumbnails/' . $item->product->image) }}" 
                                                     alt="" style="width: 50px; height: 50px; object-fit: cover; margin-right: 15px;">
                                            @else
                                                <div style="width: 50px; height: 50px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                                    <i class="fa fa-image"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $item->product_name ?? 'Product Not Found' }}</strong>
                                                @if($item->product)
                                                    <br><small class="text-muted">{{ $item->product->category->name ?? '' }} - {{ $item->product->brand->name ?? '' }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">${{ number_format($item->price, 2) }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-center">${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($order->shippingAddress)
                <div class="order-details p-4">
                    <h5>Shipping Address</h5>
                    <div class="row">
                        <div class="col-md-8">
                            <p><strong>{{ $order->shippingAddress->name }}</strong></p>
                            <p>{{ $order->shippingAddress->address }}</p>
                            <p>{{ $order->shippingAddress->locality }}</p>
                            <p>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->zip }}</p>
                            @if($order->shippingAddress->landmark)
                                <p><strong>Landmark:</strong> {{ $order->shippingAddress->landmark }}</p>
                            @endif
                            <p><strong>Phone:</strong> {{ $order->shippingAddress->phone }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <div class="order-details p-4">
                    <h5>Order Summary</h5>
                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Subtotal:</strong></td>
                                    <td class="text-end">${{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tax:</strong></td>
                                    <td class="text-end">${{ number_format($order->tax, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Shipping:</strong></td>
                                    <td class="text-end">${{ number_format($order->shipping, 2) }}</td>
                                </tr>
                                @if($order->discount > 0)
                                <tr>
                                    <td><strong>Discount:</strong></td>
                                    <td class="text-end text-success">-${{ number_format($order->discount, 2) }}</td>
                                </tr>
                                @endif
                                <tr class="border-top">
                                    <td><strong>Total:</strong></td>
                                    <td class="text-end"><strong>${{ number_format($order->total, 2) }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                @if($order->notes)
                <div class="order-details p-4">
                    <h5>Order Notes</h5>
                    <p>{{ $order->notes }}</p>
                </div>
                @endif

                @if(Session::has('status'))
                <div class="alert alert-success mt-3">{{ Session::get('status') }}</div>
                @endif

                @if($order->status === 'ordered')
                <div class="order-details p-4 text-end">
                    <form action="{{ route('user.account_cancel_order') }}" method="POST" class="d-inline-block">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="order_id" value="{{ $order->id }}" />
                        <button type="submit" class="btn btn-danger">Cancel Order</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </section>
</main>
@endsection
