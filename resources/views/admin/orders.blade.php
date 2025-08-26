@extends('layouts.admin')
@section('content')
<style>
    /* High-contrast black & white for admin orders */
    .table { color: #111; }
    .table thead th {
        background-color: #000 !important;
        color: #fff !important;
        padding: .85rem 1rem !important;
        border-color: #000 !important;
        font-weight: 700;
        letter-spacing: .2px;
    }
    .table-bordered > :not(caption) > tr > th,
    .table-bordered > :not(caption) > tr > td { border-color: #000 !important; }
    .table tbody td { padding: .85rem 1rem !important; }
    .table-striped tbody tr:nth-of-type(odd) { background-color: #fafafa; }
    .table-striped tbody tr:nth-of-type(even) { background-color: #fff; }

    /* Normalize badges to b/w */
    .badge { border-radius: 6px; font-weight: 600; padding: .35rem .6rem; }
    .badge.bg-warning, .badge.bg-success, .badge.bg-danger, .badge.bg-secondary, .badge.bg-info, .badge.bg-primary {
        background-color: #000 !important; color: #fff !important;
    }

    .list-icon-function .item.eye i { color: #000; }
    .breadcrumbs .text-tiny, h3 { color: #000; }
</style>
<div class="main-content-inner">                            
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Orders</h3>
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
                    <div class="text-tiny">All Orders</div>
                </li>
            </ul>
        </div>
        
        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search">
                        <fieldset class="name">
                            <input type="text" placeholder="Search here..." class="" name="search" tabindex="2" value="{{ request('search') }}" aria-required="true" required="">
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>                
            </div>
            <div class="wg-table table-all-user">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 80px">Order No</th>
                                <th>Customer</th>
                                <th class="text-center">Phone</th>
                                <th class="text-center">Subtotal</th>
                                <th class="text-center">Tax</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Order Date</th>
                                <th class="text-center">Total Items</th>
                                <th class="text-center">Payment Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                            <tr>
                                <td class="text-center">{{ '1' . str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>  
                                <td class="text-center">{{ $order->user->name ?? 'N/A' }}</td>
                                <td class="text-center">{{ $order->shippingAddress->phone ?? 'N/A' }}</td>
                                <td class="text-center">${{ number_format($order->subtotal, 2) }}</td>
                                <td class="text-center">${{ number_format($order->tax, 2) }}</td>
                                <td class="text-center">${{ number_format($order->total, 2) }}</td>
                                <td class="text-center">
                                    @if($order->status == 'ordered')
                                        <span class="badge bg-warning">Ordered</span>
                                    @elseif($order->status == 'delivered')
                                        <span class="badge bg-success">Delivered</span>
                                    @elseif($order->status == 'canceled')
                                        <span class="badge bg-danger">Canceled</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="text-center">{{ $order->items->count() }}</td>
                                <td class="text-center">
                                    @if($order->payment_status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($order->payment_status == 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($order->payment_status == 'failed')
                                        <span class="badge bg-danger">Failed</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($order->payment_status) }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.order.details', ['id' => $order->id]) }}">
                                        <div class="list-icon-function view-icon">
                                            <div class="item eye">
                                                <i class="icon-eye"></i>
                                            </div>                                        
                                        </div>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center">No orders found</td>
                            </tr>
                            @endforelse                                  
                        </tbody>
                    </table>                
                </div>
            </div>
            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">                
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
