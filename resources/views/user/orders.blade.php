@extends('layouts.app')
@section('content')
<style>
    /* High-contrast black & white theme for orders table */
    .table { color: #111; }
    .table thead th {
        background-color: #000 !important;
        color: #fff !important;
        padding: 0.9rem 1.2rem !important;
        border-color: #000 !important;
        text-transform: none;
        font-weight: 700;
        letter-spacing: .2px;
    }
    .table-bordered > :not(caption) > tr > th, 
    .table-bordered > :not(caption) > tr > td {
        border-width: 1px;
        border-color: #000 !important;
    }
    .table tbody td { padding: .9rem 1.2rem !important; }
    .table-striped tbody tr:nth-of-type(odd) { background-color: #fafafa; }
    .table-striped tbody tr:nth-of-type(even) { background-color: #fff; }

    /* Unify badges to black & white for accessibility */
    .badge { border-radius: 6px; font-weight: 600; padding: .35rem .6rem; }
    .badge.bg-warning, .badge.bg-success, .badge.bg-danger, .badge.bg-secondary {
        background-color: #000 !important; color: #fff !important;
    }

    /* Eye icon cell alignment */
    .list-icon-function .item.eye i { color: #000; }

    /* Title */
    .page-title { color: #000; font-weight: 800; }
</style>
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
        <h2 class="page-title">Orders</h2>
        <div class="row">
            <div class="col-lg-2">
                @include('user.account-nav')
            </div>
            <div class="col-lg-10">
                <div class="wg-table table-all-user">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 120px">Order No</th>
                                    <th>Order Date</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Payment Status</th>
                                    <th class="text-center">Items</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                <tr>
                                    <td class="text-center">{{ '1' . str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>  
                                    <td class="text-center">{{ $order->created_at->format('M d, Y') }}</td>
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
                                    <td class="text-center">${{ number_format($order->total, 2) }}</td>
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
                                    <td class="text-center">{{ $order->items->count() }}</td>
                                    <td class="text-center">                                        
                                        <a href="{{ route('user.order.details', ['id' => $order->id]) }}">
                                            <div class="list-icon-function view-icon">
                                                <div class="item eye">
                                                    <i class="fa fa-eye"></i>
                                                </div>                                        
                                            </div>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No orders found</td>
                                </tr>
                                @endforelse                                  
                            </tbody>
                        </table>                
                    </div>
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">                
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
