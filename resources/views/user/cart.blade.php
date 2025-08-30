@extends('layouts.app')

@section('content')
<style>
    .cart-totals td {
        text-align: right;
    }
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
        color: var(--text-primary);
        margin-bottom: 2rem;
        text-align: center;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
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
    .shopping-cart {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 3rem;
        align-items: start;
    }
    .cart-table__wrapper {
        background: var(--bg-primary);
        border-radius: var(--radius-lg);
        padding: 2rem;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border-color);
    }
    .cart-table {
        width: 100%;
        border-collapse: collapse;
    }
    .cart-table th {
        background: var(--bg-secondary);
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: var(--text-primary);
        border-bottom: 2px solid var(--border-color);
    }
    .cart-table td {
        padding: 1rem;
        border-bottom: 1px solid #f1f3f4;
        vertical-align: middle;
    }
    .shopping-cart__product-item img {
        border-radius: 10px;
        border: 2px solid #e9ecef;
    }
    .shopping-cart__product-item__detail h4 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #212529;
        margin-bottom: 0.5rem;
    }
    .shopping-cart__product-item__options {
        list-style: none;
        padding: 0;
        margin: 0;
        font-size: 0.9rem;
        color: #6c757d;
    }
    .shopping-cart__product-item__options li {
        margin-bottom: 0.25rem;
    }
    .shopping-cart__product-price {
        font-size: 1.2rem;
        font-weight: 700;
        color: #212529;
    }
    .shopping-cart__subtotal {
        font-size: 1.1rem;
        font-weight: 600;
        color: #28a745;
    }
    .qty-control {
        display: flex;
        align-items: center;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        overflow: hidden;
        width: fit-content;
    }
    .qty-control__number {
        border: none;
        width: 60px;
        text-align: center;
        padding: 0.5rem;
        font-weight: 600;
    }
    .qty-control__reduce,
    .qty-control__increase {
        background: #f8f9fa;
        padding: 0.5rem 0.75rem;
        cursor: pointer;
        font-weight: 700;
        color: #212529;
        transition: background 0.2s ease;
    }
    .qty-control__reduce:hover,
    .qty-control__increase:hover {
        background: #e9ecef;
    }
    .remove-cart {
        color: #dc3545;
        text-decoration: none;
        padding: 0.5rem;
        border-radius: 50%;
        transition: all 0.2s ease;
    }
    .remove-cart:hover {
        background: #dc3545;
        color: white;
    }
    .cart-table-footer {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #f1f3f4;
    }
    .cart-table-footer .form-control {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem 1rem;
    }
    .btn-link {
        background: none;
        border: none;
        color: #212529;
        font-weight: 600;
        text-decoration: none;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .btn-link:hover {
        background: #f8f9fa;
        color: #212529;
    }
    .btn-light {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        color: #6c757d;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .btn-light:hover {
        background: #e9ecef;
        border-color: #dee2e6;
    }
    .shopping-cart__totals-wrapper {
        position: sticky;
        top: 2rem;
    }
    .shopping-cart__totals {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    .shopping-cart__totals h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #212529;
        margin-bottom: 1.5rem;
        text-align: center;
    }
    .cart-totals {
        width: 100%;
        border-collapse: collapse;
    }
    .cart-totals th,
    .cart-totals td {
        padding: 1rem 0;
        border-bottom: 1px solid #f1f3f4;
        font-size: 1rem;
    }
    .cart-totals th {
        text-align: left;
        font-weight: 600;
        color: #495057;
    }
    .cart-totals td {
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
    .btn-checkout {
        background: #212529;
        border: 2px solid #212529;
        color: white;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1.1rem;
        text-decoration: none;
        display: block;
        text-align: center;
        transition: all 0.2s ease;
    }
    .btn-checkout:hover {
        background: #000;
        border-color: #000;
        color: white;
        transform: translateY(-2px);
    }
    .empty-cart {
        text-align: center;
        padding: 4rem 2rem;
        color: #6c757d;
    }
    .empty-cart i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }
    .empty-cart h5 {
        color: #212529;
        margin-bottom: 1rem;
    }
    .btn-info {
        background: #17a2b8;
        border-color: #17a2b8;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        display: inline-block;
        transition: all 0.2s ease;
    }
    .btn-info:hover {
        background: #138496;
        border-color: #138496;
        color: white;
        transform: translateY(-1px);
    }
    @media (max-width: 768px) {
        .shopping-cart {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        .checkout-steps {
            flex-direction: column;
            gap: 1rem;
        }
        .cart-table__wrapper {
            padding: 1rem;
        }
        .cart-table th,
        .cart-table td {
            padding: 0.5rem;
        }
    }
</style>

<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    
    <section class="shop-checkout container">
        <h2 class="page-title">Shopping Cart</h2>
        
        <div class="checkout-steps">
            <a href="javascript:void(0);" class="checkout-steps__item active">
                <span class="checkout-steps__item-number">01</span>
                <span class="checkout-steps__item-title">
                    <span>Shopping Bag</span>
                    <em>Manage Your Items List</em>
                </span>
            </a>
            <a href="javascript:void(0);" class="checkout-steps__item">
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
        
        <div class="shopping-cart">
            @if(count($products) > 0)
                <div class="cart-table__wrapper">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th></th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $item)
                            <tr>
                                <td>
                                    <div class="shopping-cart__product-item">
                                        @if($item['image'])
                                            <img loading="lazy" src="{{ asset('uploads/products/thumbnails/'.$item['image']) }}" 
                                                 width="120" height="120" alt="{{ $item['name'] }}" />
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                                 style="width: 120px; height: 120px;">
                                                <i class="icon-image text-muted" style="font-size: 2rem;"></i>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="shopping-cart__product-item__detail">
                                        <h4>{{ $item['name'] }}</h4>
                                        <ul class="shopping-cart__product-item__options">
                                            <li>Category: {{ $item['category'] }}</li>
                                            <li>Brand: {{ $item['brand'] }}</li>
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    <span class="shopping-cart__product-price">${{ number_format($item['price'], 2) }}</span>
                                </td>
                                <td>
                                    <div class="qty-control position-relative">
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" 
                                               min="1" class="qty-control__number text-center" 
                                               onchange="updateQuantity({{ $item['id'] }}, this.value)">
                                        <div class="qty-control__reduce" onclick="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] - 1 }})">-</div>
                                        <div class="qty-control__increase" onclick="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }})">+</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="shopping-cart__subtotal">${{ number_format($item['subtotal'], 2) }}</span>
                                </td>
                                <td>
                                    <a href="javascript:void(0)" class="remove-cart" onclick="removeFromCart({{ $item['id'] }})">
                                        <svg width="10" height="10" viewBox="0 0 10 10" fill="#767676" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.259435 8.85506L9.11449 0L10 0.885506L1.14494 9.74056L0.259435 8.85506Z" />
                                            <path d="M0.885506 0.0889838L9.74057 8.94404L8.85506 9.82955L0 0.97449L0.885506 0.0889838Z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <div class="cart-table-footer">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="position-relative">
                                    <input class="form-control" type="text" id="couponCode" placeholder="Enter coupon code" value="{{ session('applied_coupon_code', '') }}">
                                    <button class="btn-link fw-medium position-absolute top-0 end-0 h-100 px-4" type="button" onclick="applyCoupon()">
                                        APPLY COUPON
                                    </button>
                                </div>
                                <div id="couponMessage" class="mt-2" style="display: none;"></div>
                            </div>
                            <div class="col-md-4 text-end">
                                <button class="btn btn-light" type="button" onclick="clearCart()">CLEAR CART</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="shopping-cart__totals-wrapper">
                    <div class="sticky-content">
                        <div class="shopping-cart__totals">
                            <h3>Cart Totals</h3>
                            <table class="cart-totals">
                                <tbody>
                                    <tr>
                                        <th>Subtotal</th>
                                        <td>${{ number_format($subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>SHIPPING</th>
                                        <td class="text-right">Free</td>
                                    </tr>
                                    <tr>
                                        <th>VAT</th>
                                        <td>${{ number_format($tax, 2) }}</td>
                                    </tr>
                                    @if(session('applied_coupon'))
                                    <tr class="text-success">
                                        <th>Discount ({{ session('applied_coupon_code') }})</th>
                                        <td>
                                            -${{ number_format(session('applied_coupon_discount', 0), 2) }}
                                            <button class="btn btn-sm btn-outline-danger ms-2" onclick="removeCoupon()" title="Remove coupon">
                                                <i class="icon-x"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endif
                                    <tr class="cart-total">
                                        <th>Total</th>
                                        <td>${{ number_format(session('applied_coupon') ? session('applied_coupon_final_total', $total) : $total, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mobile_fixed-btn_wrapper">
                            <div class="button-wrapper container">
                                <a href="{{ route('user.checkout') }}" class="btn btn-checkout">PROCEED TO CHECKOUT</a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-cart">
                    <i class="icon-shopping-cart"></i>
                    <h5>Your cart is empty</h5>
                    <p>No items found in your shopping cart</p>
                    <a href="{{ route('user.shop') }}" class="btn btn-info">Shop Now</a>
                </div>
            @endif
        </div>
    </section>
</main>

@push('scripts')
<script>
    function updateQuantity(productId, quantity) {
        if (quantity < 1) return;
        
        fetch('{{ route("cart.update") }}', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update header cart counts immediately
                const headerCount = document.getElementById('headerCartCount');
                const userCount = document.getElementById('userCartCount');
                if (headerCount) headerCount.innerText = data.cart_count;
                if (userCount) userCount.innerText = data.cart_count;
                
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating quantity');
        });
    }
    
    function removeFromCart(productId) {
        if (confirm('Are you sure you want to remove this item from your cart?')) {
            fetch('{{ route("cart.remove") }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId
                })
            })
            .then(response => response.json())
                    .then(data => {
            if (data.success) {
                // Update header cart counts immediately
                const headerCount = document.getElementById('headerCartCount');
                const userCount = document.getElementById('userCartCount');
                if (headerCount) headerCount.innerText = data.cart_count;
                if (userCount) userCount.innerText = data.cart_count;
                
                location.reload();
            } else {
                alert(data.message);
            }
        })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while removing item');
            });
        }
    }
    
    function applyCoupon() {
        const couponCode = document.getElementById('couponCode').value.trim();
        const cartTotal = {{ $total ?? 0 }};
        
        console.log('Applying coupon:', couponCode, 'Cart total:', cartTotal);
        
        if (!couponCode) {
            showCouponMessage('Please enter a coupon code', 'danger');
            return;
        }
        
        if (cartTotal <= 0) {
            showCouponMessage('Cart total must be greater than 0', 'danger');
            return;
        }
        
        fetch('{{ route("coupons.apply") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                coupon_code: couponCode,
                cart_total: cartTotal
            })
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                showCouponMessage(data.message, 'success');
                // Store coupon info in session via page reload
                location.reload();
            } else {
                showCouponMessage(data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showCouponMessage('An error occurred while applying coupon', 'danger');
        });
    }
    
    function showCouponMessage(message, type) {
        const messageDiv = document.getElementById('couponMessage');
        messageDiv.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
        messageDiv.style.display = 'block';
        
        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 5000);
    }
    
    function removeCoupon() {
        fetch('{{ route("coupons.remove") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while removing coupon');
        });
    }
    
    function clearCart() {
        if (confirm('Are you sure you want to clear your entire cart?')) {
            fetch('{{ route("cart.clear") }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update header cart counts immediately
                    const headerCount = document.getElementById('headerCartCount');
                    const userCount = document.getElementById('userCartCount');
                    if (headerCount) headerCount.innerText = data.cart_count;
                    if (userCount) userCount.innerText = data.cart_count;
                    
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while clearing cart');
            });
        }
    }
</script>
@endpush
@endsection
