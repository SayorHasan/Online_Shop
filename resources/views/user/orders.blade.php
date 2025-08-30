@extends('layouts.app')
@section('content')
<style>
    /* Professional Orders Page Styling */
    .orders-container {
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }
    
    .orders-header {
        background: #ffffff;
        color: #333;
        padding: 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid #e9ecef;
    }
    
    .orders-header::before {
        display: none;
    }
    
    .orders-header h2 {
        position: relative;
        z-index: 2;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #333;
    }
    
    .orders-header p {
        position: relative;
        z-index: 2;
        font-size: 1.1rem;
        color: #666;
        margin-bottom: 0;
    }
    
    .table {
        color: var(--text-primary);
        margin-bottom: 0;
    }
    
    .table thead th {
        background: var(--bg-secondary) !important;
        color: var(--text-primary) !important;
        padding: 1.25rem 1rem !important;
        border-color: var(--border-color) !important;
        text-transform: none;
        font-weight: 600;
        letter-spacing: 0.025em;
        font-size: 0.9rem;
        text-align: center;
    }
    
    .table-bordered > :not(caption) > tr > th, 
    .table-bordered > :not(caption) > tr > td {
        border-width: 1px;
        border-color: var(--border-color) !important;
    }
    
    .table tbody td { 
        padding: 1.25rem 1rem !important;
        vertical-align: middle;
        text-align: center;
    }
    
    .table-striped tbody tr:nth-of-type(odd) { 
        background-color: var(--bg-primary); 
    }
    
    .table-striped tbody tr:nth-of-type(even) { 
        background-color: var(--bg-secondary); 
    }
    
    .table tbody tr:hover {
        background-color: var(--bg-tertiary);
        transition: var(--transition);
    }

    /* Professional Badge System */
    .badge { 
        border-radius: var(--radius-sm); 
        font-weight: 600; 
        padding: 0.5rem 0.75rem; 
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .badge.bg-warning {
        background: var(--warning-gradient) !important;
        color: white !important;
    }
    
    .badge.bg-success {
        background: var(--success-gradient) !important;
        color: white !important;
    }
    
    .badge.bg-danger {
        background: var(--danger-gradient) !important;
        color: white !important;
    }
    
    .badge.bg-secondary {
        background: var(--bg-dark) !important;
        color: white !important;
    }

    /* Professional View Icon */
    .list-icon-function .item.eye {
        background: var(--primary-gradient);
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
        cursor: pointer;
    }
    
    .list-icon-function .item.eye:hover {
        transform: scale(1.1);
        box-shadow: var(--shadow-md);
    }
    
    .list-icon-function .item.eye i {
        color: white;
        font-size: 1rem;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--text-muted);
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
</style>
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
        <div class="orders-container">
            <div class="orders-header">
                <h2>My Orders</h2>
                <p>Track your order history and current status</p>
            </div>
            
            <div class="row m-0">
                <div class="col-lg-2 p-0">
                    @include('user.account-nav')
                </div>
                <div class="col-lg-10 p-4">
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
                                            <span class="badge" style="background: #000; color: #fff; font-weight: bold;">Delivered</span>
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
                                            <span class="badge" style="background: #000; color: #fff; font-weight: bold;">Paid</span>
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
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <i class="icon-package"></i>
                                            <h5>No Orders Yet</h5>
                                            <p>Start shopping to see your orders here</p>
                                            <a href="{{ route('user.shop') }}" class="btn btn-primary">Start Shopping</a>
                                        </div>
                                    </td>
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
