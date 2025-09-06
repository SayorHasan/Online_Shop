@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    
    <!-- Shop Header -->
    <section class="shop-header container mb-5">  
        <!-- Highlighted Product Message -->
        @if($highlightedProduct)
        <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="icon-info-circle me-3 fs-4"></i>
                <div>
                    <strong>Product Highlight:</strong> You're viewing the shop filtered for 
                    <strong>{{ $highlightedProduct->name }}</strong> ({{ $highlightedProduct->category->name }} category).
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="page-title mb-3">Shop</h1>
                <p class="text-muted">Discover our amazing collection of products</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="d-flex align-items-center justify-content-end gap-3">
                    <div class="cart-icon-wrapper">
                        <a href="{{ route('user.cart') }}" class="btn btn-dark text-white position-relative px-4 py-2 rounded-3">
                            <i class="icon-shopping-cart me-2"></i>Cart
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count" id="cartCount">
                                {{ Session::get('cart', []) ? count(Session::get('cart', [])) : 0 }}
                            </span>
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Shop Content -->
    <section class="shop-content container">
        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-3 mb-4">
                <div class="shop-sidebar">
                    <div class="card" style="border: none; box-shadow: var(--shadow-lg);">
                        <div class="card-header" style="background: var(--primary-gradient); color: white; border: none;">
                            <h5 class="mb-0">
                                <i class="icon-filter me-2"></i>Filters
                            </h5>
                        </div>
                        <div class="card-body" style="background: var(--bg-primary);">
                            <!-- Search -->
                            <div class="mb-4">
                                <h6>Search</h6>
                                <form method="GET" action="{{ route('user.shop') }}" class="form-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search products..." 
                                               name="search" value="{{ request('search') }}">
                                        <button class="btn btn-dark text-white" type="submit">
                                            <i class="icon-search"></i>
                                            <span class="ms-1">Search</span>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Categories -->
                            <div class="mb-4">
                                <h6>Categories</h6>
                                <div class="list-group list-group-flush">
                                    @foreach($categories as $category)
                                        <a href="{{ request()->fullUrlWithQuery(['category' => $category->id]) }}" 
                                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center
                                                  {{ request('category') == $category->id ? 'active' : '' }}">
                                            {{ $category->name }}
                                            <span class="badge bg-primary rounded-pill">{{ $category->products_count }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Brands -->
                            <div class="mb-4">
                                <h6>Brands</h6>
                                <div class="list-group list-group-flush">
                                    @foreach($brands as $brand)
                                        <a href="{{ request()->fullUrlWithQuery(['brand' => $brand->id]) }}" 
                                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center
                                                  {{ request('brand') == $brand->id ? 'active' : '' }}">
                                            {{ $brand->name }}
                                            <span class="badge bg-primary rounded-pill">{{ $brand->products_count }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Stock Status Filter -->
                            <div class="mb-4">
                                <h6>Stock Status</h6>
                                <div class="list-group list-group-flush">
                                    <a href="{{ request()->fullUrlWithQuery(['stock' => 'in_stock']) }}" 
                                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center
                                              {{ request('stock') == 'in_stock' ? 'active' : '' }}">
                                        <span class="d-flex align-items-center">
                                            <span class="badge bg-success me-2" style="width: 12px; height: 12px;"></span>
                                            In Stock
                                        </span>
                                        <span class="badge bg-success rounded-pill">{{ $shopStats['in_stock_products'] }}</span>
                                    </a>
                                    <a href="{{ request()->fullUrlWithQuery(['stock' => 'out_of_stock']) }}" 
                                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center
                                              {{ request('stock') == 'out_of_stock' ? 'active' : '' }}">
                                        <span class="d-flex align-items-center">
                                            <span class="badge bg-danger me-2" style="width: 12px; height: 12px;"></span>
                                            Out of Stock
                                        </span>
                                        <span class="badge bg-danger rounded-pill">{{ $shopStats['out_of_stock_products'] }}</span>
                                    </a>
                                    <a href="{{ request()->fullUrlWithQuery(['stock' => 'low_stock']) }}" 
                                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center
                                              {{ request('stock') == 'low_stock' ? 'active' : '' }}">
                                        <span class="d-flex align-items-center">
                                            <span class="badge bg-warning me-2" style="width: 12px; height: 12px;"></span>
                                            Low Stock
                                        </span>
                                        <span class="badge bg-warning rounded-pill">{{ \App\Models\Product::where('quantity', '<=', 5)->where('quantity', '>', 0)->count() }}</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Price Range -->
                            <div class="mb-4">
                                <h6>Price Range</h6>
                                <div class="price-range">
                                    <form method="GET" action="{{ route('user.shop') }}" id="priceFilterForm">
                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                        <input type="hidden" name="category" value="{{ request('category') }}">
                                        <input type="hidden" name="brand" value="{{ request('brand') }}">
                                        <input type="range" class="form-range" id="priceRange" name="max_price" min="0" max="1000" step="10" value="{{ request('max_price', 1000) }}">
                                        <div class="d-flex justify-content-between">
                                            <span>$0</span>
                                            <span id="priceValue">${{ request('max_price', 1000) }}</span>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-outline-primary mt-2 w-100">Apply</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Clear Filters -->
                            <div class="text-center">
                                <a href="{{ route('user.shop') }}" class="btn btn-outline-secondary btn-sm">Clear Filters</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Featured Products Sidebar -->
                    @if($featuredProducts->count() > 0)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="icon-star text-warning me-2"></i>Featured Products</h6>
                        </div>
                        <div class="card-body p-0">
                            @foreach($featuredProducts->take(5) as $featuredProduct)
                                <div class="featured-product-item d-flex align-items-center p-3 border-bottom">
                                    @if($featuredProduct->image)
                                        <img src="{{ asset('uploads/products/thumbnails/'.$featuredProduct->image) }}" 
                                             alt="{{ $featuredProduct->name }}" 
                                             class="me-3" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center me-3" 
                                             style="width: 50px; height: 50px; border-radius: 8px;">
                                            <i class="icon-image text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1" style="font-size: 0.9rem;">{{ $featuredProduct->name }}</h6>
                                        <div class="text-primary fw-bold" style="font-size: 0.8rem;">
                                            ${{ number_format($featuredProduct->sale_price ?: $featuredProduct->regular_price, 2) }}
                                        </div>
                                    </div>
                                    <a href="{{ route('user.product.details', $featuredProduct->id) }}" 
                                       class="btn btn-sm btn-outline-primary">View</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Products ({{ $products->count() }})</h5>
                    <div class="view-mode-toggle">
                        <button class="btn btn-outline-primary btn-sm" id="gridView">
                            <i class="icon-grid me-1"></i>Grid
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" id="listView">
                            <i class="icon-list me-1"></i>List
                        </button>
                    </div>
                </div>

                @if($products->count() > 0)
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3" id="products-grid">
                        @foreach($products as $product)
                        <div class="col mb-4">
                            <div class="product-card h-100 hover-lift" style="background: var(--bg-primary); border-radius: var(--radius-lg); border: 2px solid var(--border-color); box-shadow: var(--shadow-sm); transition: var(--transition); overflow: hidden; position: relative;">
                                <div class="pc__img-wrapper position-relative">
                                    <div class="pc__img-slider">
                                        <div class="pc__img-slide">
                                            @if($product->image)
                                                <img loading="lazy" src="{{asset('uploads/products/thumbnails/'.$product->image)}}" 
                                                     alt="{{$product->name}}" class="pc__img">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                                    <i class="icon-image text-muted" style="font-size: 3rem;"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Product Labels -->
                                    <div class="pc-labels position-absolute top-0 start-0 p-2">
                                        @if($product->featured == 1)
                                            <span class="pc-label pc-label_new me-2">Featured</span>
                                        @endif
                                        @if($product->stock_status == 'out_of_stock')
                                            <span class="pc-label bg-danger text-white">Out of Stock</span>
                                        @elseif($product->quantity <= 5)
                                            <span class="pc-label bg-warning text-dark">Low Stock ({{ $product->quantity }})</span>
                                        @else
                                            <span class="pc-label bg-success text-white">In Stock ({{ $product->quantity }})</span>
                                        @endif
                                    </div>

                                    <!-- Wishlist Button -->
                                    <div class="position-absolute top-0 end-0 p-2">
                                        <button class="wishlist-btn heart-btn {{ in_array($product->id, $wishlistProductIds ?? []) ? 'active' : '' }}" title="Add to Wishlist" data-product-id="{{ $product->id }}" data-active="{{ in_array($product->id, $wishlistProductIds ?? []) ? 1 : 0 }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="heart-svg"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"/></svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="pc__info p-3">
                                    <p class="pc__category text-primary mb-1">{{$product->category->name ?? 'N/A'}}</p>
                                    <h6 class="pc__title mb-2">
                                        <a href="{{ route('user.product.details', $product->id) }}" class="text-decoration-none text-dark">{{$product->name}}</a>
                                    </h6>
                                    
                                    <div class="product-card__price d-flex align-items-center mb-2">
                                        @if($product->sale_price && $product->sale_price < $product->regular_price)
                                            <span class="text-decoration-line-through text-muted me-2">${{$product->regular_price}}</span>
                                            <span class="fw-bold text-dark">${{$product->sale_price}}</span>
                                            <span class="badge bg-dark ms-2">{{round(($product->regular_price - $product->sale_price)*100/$product->regular_price)}}% OFF</span>
                                        @else
                                            <span class="fw-bold text-dark">${{$product->regular_price}}</span>
                                        @endif
                                    </div>
                                    
                                    <div class="product-card__review d-flex align-items-center mb-3">
                                        <div class="reviews-group d-flex me-2">
                                            <i class="icon-star text-warning"></i>
                                            <i class="icon-star text-warning"></i>
                                            <i class="icon-star text-warning"></i>
                                            <i class="icon-star text-warning"></i>
                                            <i class="icon-star text-warning"></i>
                                        </div>
                                        <span class="reviews-note text-muted">5.0 ({{ rand(10, 100) }}+ reviews)</span>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="action-buttons d-flex gap-2">
                                        <!-- View Button -->
                                        <a href="{{ route('user.product.details', $product->id) }}" 
                                           class="btn btn-outline-primary flex-fill" style="border-radius: var(--radius-md);">
                                            <i class="icon-eye me-2"></i>View
                                        </a>
                                        
                                        <!-- Add to Cart Button -->
                                        @if($product->stock_status == 'out_of_stock')
                                            <button class="btn btn-secondary flex-fill" disabled style="border-radius: var(--radius-md);">Out of Stock</button>
                                        @else
                                            <button class="btn btn-primary add-to-cart-btn flex-fill" 
                                                    data-product-id="{{ $product->id }}"
                                                    data-product-name="{{ $product->name }}"
                                                    data-product-price="{{ $product->sale_price ?: $product->regular_price }}"
                                                    style="border-radius: var(--radius-md);">
                                                <i class="icon-shopping-cart me-2"></i>Add to Cart
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-5">
                        {{$products->withQueryString()->links('pagination::bootstrap-5')}}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="icon-package text-muted" style="font-size: 4rem;"></i>
                        <h5 class="mt-3">No products found</h5>
                        <p class="text-muted">Try adjusting your filters or check back later for new products.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
</main>
@endsection

@push('styles')
<style>
  .heart-btn{width:34px;height:34px;border:0;background:#ffffffcc;border-radius:50%;display:flex;align-items:center;justify-content:center;transition:transform .1s ease, box-shadow .2s ease}
  .heart-btn:hover{box-shadow:0 4px 10px rgba(0,0,0,.12);transform:translateY(-1px)}
  .heart-btn .heart-svg{stroke:#e11d48;transition:fill .18s ease, stroke .18s ease}
  .heart-btn.active .heart-svg{fill:#e11d48;stroke:#e11d48}
  .heart-pop{animation:heartPop .22s ease}
  @keyframes heartPop{0%{transform:scale(.92)}60%{transform:scale(1.08)}100%{transform:scale(1)}}
  
  /* Enhanced SweetAlert styling */
  .swal-wide {
      width: 400px !important;
      max-width: 90vw !important;
  }
  
  .swal-wide .swal2-popup {
      border-radius: 12px;
      padding: 2rem;
  }
  
  .swal-wide .swal2-title {
      font-size: 1.5rem;
      margin-bottom: 1rem;
  }
  
  .swal-wide .alert {
      border-radius: 8px;
      margin: 0;
  }
  
  /* Action buttons styling */
  .action-buttons {
      display: flex;
      gap: 0.5rem;
      margin-top: 1rem;
  }
  
  .action-buttons .btn {
      flex: 1;
      min-width: 0;
      white-space: nowrap;
  }
  
  .action-buttons .btn i {
      font-size: 0.875rem;
  }
  
  /* Responsive button layout */
  @media (max-width: 576px) {
      .action-buttons {
          flex-direction: column;
          gap: 0.25rem;
      }
      
      .action-buttons .btn {
          width: 100%;
      }
  }
</style>
@endpush

@push('scripts')
<script>
    $(function(){
        // Get current cart count on page load
        getCurrentCartCount();
        

        
        // Highlight and scroll to specific product if highlighted
        @if($highlightedProduct)
        $(document).ready(function() {
            // Find the product card for the highlighted product by product name
            const productCard = $('.product-card').filter(function() {
                return $(this).find('.pc__title a').text().trim() === '{{ $highlightedProduct->name }}';
            });
            
            if (productCard.length > 0) {
                // Add highlight effect
                productCard.addClass('highlighted-product');
                
                // Scroll to the product
                $('html, body').animate({
                    scrollTop: productCard.offset().top - 100
                }, 1000);
                
                // Add pulsing effect
                productCard.addClass('pulse-highlight');
                
                // Remove highlight after 5 seconds
                setTimeout(function() {
                    productCard.removeClass('pulse-highlight');
                }, 5000);
            }
        });
        @endif

        // Price range slider
        $('#priceRange').on('input', function() {
            $('#priceValue').text('$' + $(this).val());
        });

        // Auto-submit price filter when slider is released
        $('#priceRange').on('change', function(){
            $('#priceFilterForm').submit();
        });

        // Search functionality (allow empty if user wants to clear)
        $('.form-search').on('submit', function(e) {
            // no-op
        });

        // Grid/List view toggle
        $('#gridView').on('click', function() {
            $('#products-grid').removeClass('row-cols-1').addClass('row-cols-1 row-cols-md-2 row-cols-lg-3');
            $(this).removeClass('btn-outline-primary').addClass('btn-primary');
            $('#listView').removeClass('btn-primary').addClass('btn-outline-secondary');
        });

        $('#listView').on('click', function() {
            $('#products-grid').removeClass('row-cols-1 row-cols-md-2 row-cols-lg-3').addClass('row-cols-1');
            $(this).removeClass('btn-outline-secondary').addClass('btn-primary');
            $('#gridView').removeClass('btn-primary').addClass('btn-outline-primary');
        });

        // Wishlist functionality
        $('.wishlist-btn').on('click', function() {
            const productId = $(this).data('product-id');
            const btn = $(this);
            btn.prop('disabled', true);
            fetch('{{ route('wishlist.toggle') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ product_id: productId })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    if (data.added) {
                        btn.addClass('active');
                        btn.attr('title', 'Remove from Wishlist');
                        Swal.fire({
                            title: 'Added to Wishlist!',
                            text: 'Product has been added to your wishlist.',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            timer: 2000,
                            timerProgressBar: true
                        });
                        showToast('Wishlist', 'Product added to your wishlist', 'success');
                    } else {
                        btn.removeClass('active');
                        btn.attr('title', 'Add to Wishlist');
                        Swal.fire({
                            title: 'Removed from Wishlist!',
                            text: 'Product has been removed from your wishlist.',
                            icon: 'info',
                            confirmButtonText: 'OK',
                            timer: 2000,
                            timerProgressBar: true
                        });
                        showToast('Wishlist', 'Removed from wishlist', 'error');
                    }
                }
            })
            .finally(() => btn.prop('disabled', false));
        });

        // Add to cart functionality
        $('.add-to-cart-btn').on('click', function() {
            const productId = $(this).data('product-id');
            const productName = $(this).data('product-name');
            const productPrice = $(this).data('product-price');
            const btn = $(this);
            

            
            // Disable button to prevent double-click
            btn.prop('disabled', true).html('<i class="icon-spinner fa-spin me-2"></i>Adding...');
            
            // AJAX call to add to cart
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {

                if (data.success) {
                    // Update cart count using centralized function - use total_quantity if available
                    const cartCount = data.total_quantity || data.cart_count || 0;
                    updateCartCount(cartCount);
                    
                    // Show success message
                    btn.removeClass('btn-primary').addClass('btn-success').html('<i class="icon-check me-2"></i>Added!');
                    
                    // Reset button after 2 seconds
                    setTimeout(function() {
                        btn.removeClass('btn-success').addClass('btn-primary').prop('disabled', false).html('<i class="icon-shopping-cart me-2"></i>Add to Cart');
                    }, 2000);
                    
                    // Show SweetAlert popup notification
                    Swal.fire({
                        title: 'Added to Cart!',
                        html: `
                            <div class="text-center">
                                <div class="mb-3">
                                    <i class="fa fa-check-circle text-success" style="font-size: 3rem;"></i>
                                </div>
                                <p class="mb-2"><strong>${productName}</strong></p>
                                <p class="text-muted mb-3">has been added to your cart</p>
                                <div class="alert alert-info">
                                    <small><i class="fa fa-shopping-cart me-1"></i>Cart now contains ${cartCount} item${cartCount !== 1 ? 's' : ''}</small>
                                </div>
                            </div>
                        `,
                        icon: 'success',
                        confirmButtonText: 'Continue Shopping',
                        showCancelButton: true,
                        cancelButtonText: 'View Cart',
                        timer: 4000,
                        timerProgressBar: true,
                        customClass: {
                            popup: 'swal-wide'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Continue shopping - stay on current page
                        } else {
                            // View cart
                            window.location.href = '{{ route("user.cart") }}';
                        }
                    });
                    
                    // Also show a quick toast notification
                    showToast('Success!', productName + ' has been added to your cart', 'success');
                } else {
                    // Show error popup
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Failed to add product to cart.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    showToast('Error!', data.message, 'error');
                    btn.prop('disabled', false).html('<i class="icon-shopping-cart me-2"></i>Add to Cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while adding to cart. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                showToast('Error!', 'An error occurred while adding to cart', 'error');
                btn.prop('disabled', false).html('<i class="icon-shopping-cart me-2"></i>Add to Cart');
            });
        });

        // Function to get current cart count
        function getCurrentCartCount() {
            fetch('{{ route("cart.count") }}')
                .then(response => response.json())
                .then(data => {
                    // Use total_quantity for cart count display (shows total items, not unique products)
                    updateCartCount(data.total_quantity || data.count || 0);
                })
                .catch(error => {
                    console.log('Failed to get cart count:', error);
                });
        }

        // Function to update cart count across the page
        function updateCartCount(count) {
            // Update specific cart count elements by ID
            const cartCountElements = [
                'userCartCount',
                'headerCartCount', 
                'cartCount',
                'js-cart-items-count'
            ];
            
            cartCountElements.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.textContent = count;
                }
            });
            
            // Update elements with cart-related classes
            const cartBadges = document.querySelectorAll('.cart-badge, .cart-count, .js-cart-items-count');
            cartBadges.forEach(badge => {
                badge.textContent = count;
            });
            
            // Store in sessionStorage for cross-page updates
            sessionStorage.setItem('cart_count', count);
            
            // Dispatch custom event for other components
            window.dispatchEvent(new CustomEvent('cartUpdated', { detail: { count: count } }));
        }





        // Toast notification function
        function showToast(title, message, type) {
            const toast = `
                <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0 position-fixed top-0 end-0 m-3" 
                     role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 9999;">
                    <div class="d-flex">
                        <div class="toast-body">
                            <strong>${title}</strong><br>${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;
            
            $('body').append(toast);
            const toastElement = $('.toast').last();
            const bsToast = new bootstrap.Toast(toastElement);
            bsToast.show();
            
            // Remove toast after it's hidden
            toastElement.on('hidden.bs.toast', function() {
                $(this).remove();
            });
        }
    });
</script>
@endpush
