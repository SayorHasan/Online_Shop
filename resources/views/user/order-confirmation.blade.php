@extends('layouts.app')

@section('content')
<style>
    .order-complete {
        text-align: center;
        padding: 4rem 2rem;
        color: #6c757d;
    }
    .order-complete__message {
        background: white;
        border-radius: 15px;
        padding: 3rem;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        max-width: 600px;
        margin: 0 auto;
    }
    .order-complete__message svg {
        margin-bottom: 2rem;
    }
    .order-complete__message h3 {
        color: #212529;
        margin-bottom: 1rem;
        font-size: 2rem;
        font-weight: 700;
    }
    .order-complete__message p {
        font-size: 1.1rem;
        margin-bottom: 2rem;
        color: #6c757d;
    }
    .order-details {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 2rem;
        margin: 2rem 0;
        text-align: left;
    }
    .order-details h4 {
        color: #212529;
        margin-bottom: 1rem;
        font-size: 1.3rem;
        font-weight: 600;
    }
    .order-details p {
        margin-bottom: 0.5rem;
        color: #495057;
    }
    .order-details strong {
        color: #212529;
    }
    .btn-info {
        background: #17a2b8;
        border-color: #17a2b8;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }
    .btn-info:hover {
        background: #138496;
        border-color: #138496;
        color: white;
        transform: translateY(-1px);
    }
</style>

<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    
    <section class="container">
        <div class="order-complete">
            <div class="order-complete__message">
                <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="40" cy="40" r="40" fill="#28a745" />
                    <path d="M52.9743 35.7612C52.9743 35.3426 52.8069 34.9241 52.5056 34.6228L50.2288 32.346C49.9275 32.0446 49.5089 31.8772 49.0904 31.8772C48.6719 31.8772 47.9533 32.0446 47.952 32.346L36.9699 43.3449L32.048 38.4062C31.7467 38.1049 31.3281 37.9375 30.9096 37.9375C30.4911 37.9375 30.0725 38.1049 29.7712 38.4062L27.4944 40.683C27.1931 40.9844 27.0257 41.4029 27.0257 41.8214C27.0257 42.24 27.1931 42.6585 27.4944 42.9598L33.5547 49.0201L35.8315 51.2969C36.1328 51.5982 36.5513 51.7656 36.9699 51.7656C37.3884 51.7656 37.8069 51.5982 38.1083 51.2969L40.385 49.0201L52.5056 36.8996C52.8069 36.5982 52.9743 36.1797 52.9743 35.7612Z" fill="white" />
                </svg>
                <h3>Your order is completed!</h3>
                <p>Thank you. Your order has been received.</p>
                
                <div class="order-details">
                    <h4>Order Details</h4>
                    <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                    <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y') }}</p>
                    <p><strong>Total Amount:</strong> ${{ number_format($order->total, 2) }}</p>
                    <p><strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                </div>
                
                <a href="{{ route('user.shop') }}" class="btn btn-info">Shop More</a>
            </div>
        </div>
    </section>
</main>

@push('scripts')
<script>
    // Update cart counts to 0 since cart was cleared after order
    document.addEventListener('DOMContentLoaded', function() {
        const headerCount = document.getElementById('headerCartCount');
        const userCount = document.getElementById('userCartCount');
        
        if (headerCount) headerCount.innerText = '0';
        if (userCount) userCount.innerText = '0';
    });
</script>
@endpush
@endsection
