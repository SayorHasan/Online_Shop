@extends('layouts.admin')
@section('content')
<style>
    .table-transaction>tbody>tr:nth-of-type(odd) { --bs-table-accent-bg: #fff !important; }
    /* High-contrast black & white */
    .table { color: #111; }
    .table thead th { background-color: #000 !important; color: #fff !important; }
    .table-bordered > :not(caption) > tr > th,
    .table-bordered > :not(caption) > tr > td { border-color: #000 !important; }
    .table tbody td { padding: .85rem 1rem; }
    h3, h5, .breadcrumbs .text-tiny { color: #000; }
    .badge { background: #000 !important; color: #fff !important; border-radius: 6px; padding: .35rem .6rem; }
    .list-icon-function .item.eye i { color: #000; }
</style>
<div class="main-content-inner">                            
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Order Details</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{route('admin.index')}}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>                                                                            
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Order Details</div>
                </li>
            </ul>
        </div>       
        
        <div class="wg-box mt-5 mb-5">            
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <h5>Order Details</h5>
                </div>
                <a class="tf-button style-1 w208" href="{{route('admin.orders')}}">Back</a>
            </div>            
            <table class="table table-striped table-bordered table-transaction">
                <tr>
                    <th>Order No</th>
                    <td>{{ '1' . str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <th>Customer</th>
                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                    <th>Order Date</th>
                    <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td>{{ $order->shippingAddress->phone ?? 'N/A' }}</td>
                    <th>Payment Method</th>
                    <td>{{ ucfirst($order->payment_method) }}</td>
                    <th>Payment Status</th>
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
                    <th>Order Status</th>
                    <td colspan="5">
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
        
        <div class="wg-box mt-5">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <h5>Order Items</h5>
                </div>            
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">Subtotal</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">Brand</th>                                                        
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                        <tr>
                            <td class="pname">
                                <div class="image">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ asset('uploads/products/thumbnails/' . $item->product->image) }}" alt="" class="image" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="image-placeholder" style="width: 50px; height: 50px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                            <i class="icon-image"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="name">
                                    <a href="{{ route('public.product.details', ['id' => $item->product->id ?? 0]) }}" target="_blank" class="body-title-2">
                                        {{ $item->product_name ?? 'Product Not Found' }}
                                    </a>                                    
                                </div>  
                            </td>
                            <td class="text-center">${{ number_format($item->price, 2) }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-center">${{ number_format($item->subtotal, 2) }}</td>
                            <td class="text-center">{{ $item->product->category->name ?? 'N/A' }}</td>
                            <td class="text-center">{{ $item->product->brand->name ?? 'N/A' }}</td>
                            <td class="text-center">
                                @if($item->product)
                                    <a href="{{ route('public.product.details', ['id' => $item->product->id]) }}" target="_blank">
                                        <div class="list-icon-function view-icon">
                                            <div class="item eye">
                                                <i class="icon-eye"></i>
                                            </div>                                                                    
                                        </div>
                                    </a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach                                  
                    </tbody>
                </table>
            </div>
        </div>

        <div class="wg-box mt-5">
            <h5>Shipping Address</h5>
            @if($order->shippingAddress)
            <div class="my-account__address-item col-md-6">                
                <div class="my-account__address-item__detail">
                    <p><strong>{{ $order->shippingAddress->name }}</strong></p>
                    <p>{{ $order->shippingAddress->address }}</p>
                    <p>{{ $order->shippingAddress->locality }}</p>
                    <p>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->zip }}</p>
                    @if($order->shippingAddress->landmark)
                        <p>{{ $order->shippingAddress->landmark }}</p>
                    @endif
                    <br />                                
                    <p><strong>Phone:</strong> {{ $order->shippingAddress->phone }}</p>
                </div>
            </div>
            @else
            <p class="text-muted">No shipping address found</p>
            @endif
        </div>

        <div class="wg-box mt-5">
            <h5>Order Summary</h5>
            <table class="table table-striped table-bordered table-transaction">
                <tr>
                    <th>Subtotal</th>
                    <td>${{ number_format($order->subtotal, 2) }}</td>
                    <th>Tax</th>
                    <td>${{ number_format($order->tax, 2) }}</td>
                    <th>Shipping</th>
                    <td>${{ number_format($order->shipping, 2) }}</td>
                </tr>
                <tr>
                    <th>Discount</th>
                    <td>${{ number_format($order->discount, 2) }}</td>
                    <th>Total</th>
                    <td colspan="3"><strong>${{ number_format($order->total, 2) }}</strong></td>
                </tr>                
            </table>
        </div>

        <div class="wg-box mt-5">
            <h5>Update Order Status</h5>
            <form action="{{ route('admin.order.status.update') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="order_id" value="{{ $order->id }}" />
                <div class="row">
                    <div class="col-md-3">
                        <div class="select">
                            <select id="order_status" name="order_status" class="form-control">                            
                                <option value="ordered" {{ $order->status == 'ordered' ? 'selected' : '' }}>Ordered</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary tf-button w208">Update</button>
                    </div>                    
                </div>
            </form>
        </div>

        @if(Session::has('status'))
            <div class="alert alert-success mt-3">
                {{ Session::get('status') }}
            </div>
        @endif
    </div>
</div>
@endsection
