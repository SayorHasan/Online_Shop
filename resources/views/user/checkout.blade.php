@extends('layouts.app')

@section('content')
<style>
    .cart-total th, .cart-total td {
        color: green;
        font-weight: bold;
        font-size: 21px !important;
    }
    .shop-checkout {
        padding: 2rem 0;
    }
    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #212529;
        margin-bottom: 2rem;
        text-align: center;
    }
    .checkout-steps {
        display: flex;
        justify-content: center;
        margin-bottom: 3rem;
        gap: 2rem;
    }
    .checkout-steps__item {
        display: flex;
        align-items: center;
        gap: 1rem;
        text-decoration: none;
        color: #6c757d;
        padding: 1rem;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    .checkout-steps__item.active {
        color: #212529;
        background: #f8f9fa;
    }
    .checkout-steps__item-number {
        background: #212529;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.2rem;
    }
    .checkout-steps__item-title {
        display: flex;
        flex-direction: column;
    }
    .checkout-steps__item-title span {
        font-weight: 600;
        font-size: 1.1rem;
    }
    .checkout-steps__item-title em {
        font-style: normal;
        font-size: 0.9rem;
        opacity: 0.7;
    }
    .checkout-form {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 3rem;
        align-items: start;
    }
    .billing-info__wrapper {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    }
    .billing-info__wrapper h4 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #212529;
        margin-bottom: 1.5rem;
    }
    .my-account__address-list {
        margin-top: 1rem;
    }
    .my-account__address-item {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }
    .my-account__address-item__detail p {
        margin-bottom: 0.5rem;
        color: #495057;
        font-size: 1rem;
    }
    .my-account__address-item__detail p:first-child {
        font-weight: 600;
        color: #212529;
        font-size: 1.1rem;
    }
    .form-floating {
        margin-bottom: 1rem;
    }
    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem 1rem;
    }
    .form-control:focus {
        border-color: #212529;
        box-shadow: 0 0 0 0.2rem rgba(33, 37, 41, 0.25);
    }
    .checkout__totals-wrapper {
        position: sticky;
        top: 2rem;
    }
    .checkout__totals {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    .checkout__totals h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #212529;
        margin-bottom: 1.5rem;
        text-align: center;
    }
    .checkout-cart-items,
    .checkout-totals {
        width: 100%;
        border-collapse: collapse;
    }
    .checkout-cart-items th,
    .checkout-cart-items td,
    .checkout-totals th,
    .checkout-totals td {
        padding: 1rem 0;
        border-bottom: 1px solid #f1f3f4;
        font-size: 1rem;
    }
    .checkout-cart-items th,
    .checkout-totals th {
        text-align: left;
        font-weight: 600;
        color: #495057;
    }
    .checkout-cart-items td,
    .checkout-totals td {
        text-align: right;
        font-weight: 600;
        color: #212529;
    }
    .cart-total th,
    .cart-total td {
        color: #28a745 !important;
        font-weight: bold;
        font-size: 1.3rem !important;
        border-bottom: none;
    }
    .checkout__payment-methods {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    .form-check {
        margin-bottom: 1rem;
    }
    .form-check-input {
        margin-right: 0.75rem;
    }
    .form-check-label {
        font-weight: 500;
        color: #212529;
    }
    .policy-text {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #f1f3f4;
        font-size: 0.9rem;
        color: #6c757d;
        line-height: 1.5;
    }
    .policy-text a {
        color: #212529;
        text-decoration: underline;
    }
    .btn-primary {
        background: #212529;
        border: 2px solid #212529;
        color: white;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1.1rem;
        width: 100%;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        background: #000;
        border-color: #000;
        transform: translateY(-2px);
    }
    .btn-info {
        background: #17a2b8;
        border-color: #17a2b8;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        display: inline-block;
        font-size: 0.9rem;
        margin-left: 0.5rem;
    }
    .btn-warning {
        background: #ffc107;
        border-color: #ffc107;
        color: #212529;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        display: inline-block;
        font-size: 0.9rem;
    }
    .text-danger {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    @media (max-width: 768px) {
        .checkout-form {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        .checkout-steps {
            flex-direction: column;
            gap: 1rem;
        }
        .billing-info__wrapper,
        .checkout__totals,
        .checkout__payment-methods {
            padding: 1rem;
        }
    }
</style>

<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    
    <section class="shop-checkout container">
        <h2 class="page-title">Shipping and Checkout</h2>
        
        <div class="checkout-steps">
            <a href="{{ route('user.cart') }}" class="checkout-steps__item">
                <span class="checkout-steps__item-number">01</span>
                <span class="checkout-steps__item-title">
                    <span>Shopping Bag</span>
                    <em>Manage Your Items List</em>
                </span>
            </a>
            <a href="{{ route('user.checkout') }}" class="checkout-steps__item active">
                <span class="checkout-steps__item-number">02</span>
                <span class="checkout-steps__item-title">
                    <span>Shipping and Checkout</span>
                    <em>Checkout Your Items List</em>
                </span>
            </a>
            <a href="javascript:void(0);" class="checkout-steps__item">
                <span class="checkout-steps__item-number">03</span>
                <span class="checkout-steps__item-title">
                    <span>Confirmation</span>
                    <em>Order Confirmation</em>
                </span>
            </a>
        </div>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        <form name="checkout-form" action="{{ route('checkout.place.order') }}" method="POST">
            @csrf
            <div class="checkout-form">
                <div class="billing-info__wrapper">
                    <div class="row">
                        <div class="col-6">
                            <h4>SHIPPING DETAILS</h4> 
                        </div>
                        <div class="col-6 text-end">
                            @if($address)  
                                <a href="{{ route('user.addresses') }}" class="btn btn-info btn-sm">Change Address</a> 
                                <a href="{{ route('user.address.edit', ['id' => $address->id]) }}" class="btn btn-warning btn-sm">Edit Address</a> 
                            @endif
                        </div>
                    </div>   
                    
                    @if($address) 
                        <div class="row">
                            <div class="col-md-12">
                                <div class="my-account__address-list">
                                    <div class="my-account__address-item">                                    
                                        <div class="my-account__address-item__detail">
                                            <p>{{ $address->name }}</p>
                                            <p>{{ $address->address }}</p>
                                            <p>{{ $address->landmark }}</p>
                                            <p>{{ $address->city }}, {{ $address->state }}, {{ $address->country }}</p>
                                            <p>{{ $address->zip }}</p>
                                            <p>Phone: {{ $address->phone }}</p>                                        
                                        </div>
                                    </div>                                
                                </div>
                            </div>
                        </div>  
                    @else             
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           name="name" id="name" value="{{ old('name') }}" required>
                                    <label for="name">Full Name *</label>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           name="phone" id="phone" value="{{ old('phone') }}" required>
                                    <label for="phone">Phone Number *</label>
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('zip') is-invalid @enderror" 
                                           name="zip" id="zip" value="{{ old('zip') }}" required>
                                    <label for="zip">Pincode *</label>
                                    @error('zip')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>                        
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                           name="state" id="state" value="{{ old('state') }}" required>
                                    <label for="state">State *</label>
                                    @error('state')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>                            
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                           name="city" id="city" value="{{ old('city') }}" required>
                                    <label for="city">Town / City *</label>
                                    @error('city')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                           name="address" id="address" value="{{ old('address') }}" required>
                                    <label for="address">House no, Building Name *</label>
                                    @error('address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('locality') is-invalid @enderror" 
                                           name="locality" id="locality" value="{{ old('locality') }}" required>
                                    <label for="locality">Road Name, Area, Colony *</label>
                                    @error('locality')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>    
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('landmark') is-invalid @enderror" 
                                           name="landmark" id="landmark" value="{{ old('landmark') }}" required>
                                    <label for="landmark">Landmark *</label>
                                    @error('landmark')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>                                         
                        </div> 
                    @endif                   
                </div>
                
                <div class="checkout__totals-wrapper">
                    <div class="sticky-content">
                        <div class="checkout__totals">
                            <h3>Your Order</h3>
                            <table class="checkout-cart-items">
                                <thead>
                                    <tr>
                                        <th>PRODUCT</th>
                                        <th class="text-right">SUBTOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $item)
                                    <tr>
                                        <td>
                                            {{ $item['name'] }} x {{ $item['quantity'] }}
                                        </td>
                                        <td class="text-right">
                                            ${{ number_format($item['subtotal'], 2) }}
                                        </td>
                                    </tr>
                                    @endforeach                                    
                                </tbody>
                            </table>
                            
                            @if(session('applied_coupon'))
                                <table class="checkout-totals">
                                    <tbody>
                                        <tr>
                                            <th>Subtotal</th>
                                            <td class="text-right">${{ number_format($subtotal, 2) }}</td>
                                        </tr> 
                                        <tr>
                                            <th>Discount ({{ session('applied_coupon_code') }})</th>
                                            <td class="text-right">-${{ number_format(session('applied_coupon_discount', 0), 2) }}</td>
                                        </tr> 
                                        <tr>
                                            <th>Subtotal After Discount</th>
                                            <td class="text-right">${{ number_format(session('applied_coupon_discount', 0) > 0 ? $subtotal - session('applied_coupon_discount', 0) : $subtotal, 2) }}</td>
                                        </tr>   
                                        <tr>
                                            <th>SHIPPING</th>
                                            <td class="text-right">Free</td>
                                        </tr>                             
                                        <tr>
                                            <th>VAT</th>
                                            <td class="text-right">${{ number_format($tax, 2) }}</td>
                                        </tr>
                                        <tr class="cart-total">
                                            <th>Total</th>
                                            <td class="text-right">${{ number_format(session('applied_coupon_final_total', $total), 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            @else
                                <table class="checkout-totals">
                                    <tbody>
                                        <tr>
                                            <th>SUBTOTAL</th>
                                            <td class="text-right">${{ number_format($subtotal, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>SHIPPING</th>
                                            <td class="text-right">Free</td>
                                        </tr>
                                        <tr>
                                            <th>VAT</th>
                                            <td class="text-right">${{ number_format($tax, 2) }}</td>
                                        </tr>
                                        <tr class="cart-total">
                                            <th>TOTAL</th>
                                            <td class="text-right">${{ number_format($total, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            @endif
                        </div>
                        
                        <div class="checkout__payment-methods">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_mode" value="card" id="payment_card">
                                <label class="form-check-label" for="payment_card">
                                    Debit or Credit Card                                    
                                </label>
                            </div> 
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_mode" value="paypal" id="payment_paypal">
                                <label class="form-check-label" for="payment_paypal">
                                    Paypal                                    
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_mode" value="cod" id="payment_cod" checked>
                                <label class="form-check-label" for="payment_cod">
                                    Cash on delivery                                    
                                </label>
                            </div>
                            <div class="policy-text">
                                Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our <a href="#" target="_blank">privacy policy</a>.
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">PLACE ORDER</button>
                    </div>
                </div>
            </div>
        </form>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[name="checkout-form"]');
    
    form.addEventListener('submit', function(e) {
        console.log('Form submission started');
        
        // Check if payment method is selected
        const paymentMethod = document.querySelector('input[name="payment_mode"]:checked');
        if (!paymentMethod) {
            e.preventDefault();
            alert('Please select a payment method');
            return false;
        }
        
        console.log('Payment method selected:', paymentMethod.value);
        console.log('Form action:', form.action);
        console.log('Form method:', form.method);
        
        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Processing...';
        
        return true;
    });
});
</script>
@endsection
