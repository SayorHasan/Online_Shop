@extends('layouts.app')

@section('content')
<main>
    <!-- Product Details Header -->
    <section class="container my-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user.shop') }}">Shop</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user.shop') }}?category={{ $product->category->id }}">{{ $product->category->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>
    </section>

    <!-- Product Details Content -->
    <section class="container mb-5">
        <div class="row">
            <!-- Product Images -->
            <div class="col-lg-6 mb-4">
                <div class="product-gallery">
                    <!-- Main Image -->
                    <div class="main-image-container mb-3">
                        <img id="main-product-image" 
                             src="{{ asset('uploads/products/thumbnails/'.$product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid rounded shadow-sm"
                             style="width: 100%; height: 500px; object-fit: cover;">
                    </div>
                    
                    <!-- Thumbnail Gallery -->
                    @if(count($product->gallery) > 0)
                    <div class="thumbnail-gallery d-flex gap-2">
                        <div class="thumbnail-item active" onclick="changeMainImage('{{ asset('uploads/products/thumbnails/'.$product->image) }}')">
                            <img src="{{ asset('uploads/products/thumbnails/'.$product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="img-fluid rounded"
                                 style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;">
                        </div>
                        @foreach($product->gallery as $galleryImage)
                        <div class="thumbnail-item" onclick="changeMainImage('{{ asset('uploads/products/thumbnails/'.$galleryImage) }}')">
                            <img src="{{ asset('uploads/products/thumbnails/'.$galleryImage) }}" 
                                 alt="{{ $product->name }}" 
                                 class="img-fluid rounded"
                                 style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;">
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-6">
                <div class="product-info">
                    <h1 class="h2 mb-3">{{ $product->name }}</h1>
                    
                    <!-- Product Meta -->
                    <div class="product-meta mb-3">
                        <span class="badge bg-primary me-2">{{ $product->category->name }}</span>
                        <span class="badge bg-secondary me-2">{{ $product->brand->name }}</span>
                        <span class="badge bg-{{ $product->stock_status === 'in_stock' ? 'success' : 'danger' }}">
                            {{ ucfirst(str_replace('_', ' ', $product->stock_status)) }}
                        </span>
                    </div>

                    <!-- Product Rating -->
                    <div class="product-rating mb-3">
                        <div class="stars d-inline-block me-2">
                            <i class="fa fa-star text-warning"></i>
                            <i class="fa fa-star text-warning"></i>
                            <i class="fa fa-star text-warning"></i>
                            <i class="fa fa-star text-warning"></i>
                            <i class="fa fa-star text-muted"></i>
                        </div>
                        <span class="text-muted">(4.0 out of 5)</span>
                    </div>

                    <!-- Product Price -->
                    <div class="product-price mb-4">
                        @if($product->sale_price)
                            <span class="h3 text-danger me-3">${{ number_format($product->sale_price, 2) }}</span>
                            <span class="h5 text-muted text-decoration-line-through">${{ number_format($product->regular_price, 2) }}</span>
                            <span class="badge bg-danger ms-2">
                                {{ round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100) }}% OFF
                            </span>
                        @else
                            <span class="h3 text-primary">${{ number_format($product->regular_price, 2) }}</span>
                        @endif
                    </div>

                    <!-- Product Description -->
                    <div class="product-description mb-4">
                        <h5>Description</h5>
                        <p class="text-muted">{{ $product->description }}</p>
                    </div>

                    <!-- Product Options -->
                    <div class="product-options mb-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Quantity</label>
                                <div class="input-group" style="max-width: 150px;">
                                    <button class="btn btn-outline-secondary" type="button" onclick="decreaseQuantity()">-</button>
                                    <input type="number" class="form-control text-center" id="quantity" value="1" min="1" max="{{ $product->quantity }}">
                                    <button class="btn btn-outline-secondary" type="button" onclick="increaseQuantity()">+</button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">SKU</label>
                                <p class="form-control-plaintext">{{ $product->SKU }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Product Actions -->
                    <div class="product-actions mb-4">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <button class="btn btn-primary btn-lg w-100" onclick="addToCart()" 
                                        {{ $product->stock_status === 'out_of_stock' ? 'disabled' : '' }}>
                                    <i class="fa fa-shopping-cart me-2"></i>
                                    {{ $product->stock_status === 'in_stock' ? 'Add to Cart' : 'Out of Stock' }}
                                </button>
                            </div>
                            <div class="col-md-6 mb-2">
                                <button class="btn btn-outline-danger btn-lg w-100" onclick="addToWishlist()">
                                    <i class="fa fa-heart me-2"></i>Add to Wishlist
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Product Features -->
                    <div class="product-features">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-truck text-primary me-2"></i>
                                    <span class="small">Free Shipping</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-undo text-primary me-2"></i>
                                    <span class="small">30 Day Return</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-shield text-primary me-2"></i>
                                    <span class="small">Secure Payment</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-headset text-primary me-2"></i>
                                    <span class="small">24/7 Support</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Tabs -->
    <section class="container mb-5">
        <div class="product-tabs">
            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">
                        Description
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button" role="tab">
                        Specifications
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
                        Reviews
                    </button>
                </li>
            </ul>
            
            <div class="tab-content" id="productTabsContent">
                <div class="tab-pane fade show active" id="description" role="tabpanel">
                    <div class="p-4">
                        <h5>Product Description</h5>
                        <p>{{ $product->description }}</p>
                    </div>
                </div>
                <div class="tab-pane fade" id="specifications" role="tabpanel">
                    <div class="p-4">
                        <h5>Product Specifications</h5>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td><strong>Category:</strong></td>
                                    <td>{{ $product->category->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Brand:</strong></td>
                                    <td>{{ $product->brand->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>SKU:</strong></td>
                                    <td>{{ $product->SKU }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Stock Status:</strong></td>
                                    <td>{{ ucfirst($product->stock_status) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Quantity Available:</strong></td>
                                    <td>{{ $product->quantity }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <div class="p-4">
                        <h5>Customer Reviews</h5>
                        <p class="text-muted">No reviews yet. Be the first to review this product!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <section class="container mb-5">
        <h3 class="mb-4">Related Products</h3>
        <div class="row">
            @foreach($relatedProducts as $relatedProduct)
            <div class="col-md-3 mb-4">
                <div class="card h-100 product-card">
                    <img src="{{ asset('uploads/products/thumbnails/'.$relatedProduct->image) }}" 
                         class="card-img-top" alt="{{ $relatedProduct->name }}"
                         style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h6 class="card-title">{{ $relatedProduct->name }}</h6>
                        <p class="card-text text-muted">{{ $relatedProduct->category->name }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-primary fw-bold">
                                ${{ number_format($relatedProduct->sale_price ?? $relatedProduct->regular_price, 2) }}
                            </span>
                            <a href="{{ route('user.product.details', $relatedProduct->id) }}" 
                               class="btn btn-outline-primary btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</main>

@push('scripts')
<script>
    function changeMainImage(imageSrc) {
        document.getElementById('main-product-image').src = imageSrc;
        
        // Update active thumbnail
        document.querySelectorAll('.thumbnail-item').forEach(item => item.classList.remove('active'));
        event.target.closest('.thumbnail-item').classList.add('active');
    }

    function decreaseQuantity() {
        const quantityInput = document.getElementById('quantity');
        if (quantityInput.value > 1) {
            quantityInput.value = parseInt(quantityInput.value) - 1;
        }
    }

    function increaseQuantity() {
        const quantityInput = document.getElementById('quantity');
        const maxQuantity = {{ $product->quantity }};
        if (quantityInput.value < maxQuantity) {
            quantityInput.value = parseInt(quantityInput.value) + 1;
        }
    }

    function addToCart() {
        const quantity = document.getElementById('quantity').value;
        const productId = {{ $product->id }};
        const productName = '{{ $product->name }}';
        
        // Disable button to prevent double-click
        const btn = document.querySelector('.btn-primary');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Adding...';
        
        // AJAX call to add to cart
        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: parseInt(quantity)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                Swal.fire({
                    title: 'Added to Cart!',
                    text: `${quantity} x ${productName} has been added to your cart.`,
                    icon: 'success',
                    confirmButtonText: 'Continue Shopping',
                    showCancelButton: true,
                    cancelButtonText: 'View Cart'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Continue shopping
                        window.location.href = '{{ route("user.shop") }}';
                    } else {
                        // View cart
                        window.location.href = '{{ route("user.cart") }}';
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: data.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'An error occurred while adding to cart',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        })
        .finally(() => {
            // Re-enable button
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    }

    function addToWishlist() {
        Swal.fire({
            title: 'Added to Wishlist!',
            text: '{{ $product->name }} has been added to your wishlist.',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    }
</script>
@endpush

<style>
.thumbnail-item.active img {
    border: 2px solid #007bff;
}

.product-card {
    transition: transform 0.2s;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.nav-tabs .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
    color: #6c757d;
}

.nav-tabs .nav-link.active {
    border-bottom: 2px solid #007bff;
    color: #007bff;
    background: none;
}
</style>
@endsection
