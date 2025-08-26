@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    
    <!-- Shop Header -->
    <section class="shop-header container mb-5">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="page-title mb-3">Shop</h1>
                <p class="text-muted">Discover our amazing collection of products</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="shop-stats">
                    <span class="badge bg-primary fs-6">{{ $products->total() }} Products</span>
                    <span class="badge bg-success ms-2">{{ $categories->count() }} Categories</span>
                    <span class="badge bg-info ms-2">{{ $brands->count() }} Brands</span>
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
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Filters</h5>
                        </div>
                        <div class="card-body">
                            <!-- Search -->
                            <div class="mb-4">
                                <h6>Search</h6>
                                <form class="form-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search products..." 
                                               name="search" value="{{ request('search') }}">
                                        <button class="btn btn-outline-primary" type="submit">
                                            <i class="icon-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Categories -->
                            <div class="mb-4">
                                <h6>Categories</h6>
                                <div class="list-group list-group-flush">
                                    @foreach($categories as $category)
                                        <a href="?category={{ $category->id }}" 
                                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center
                                                  {{ request('category') == $category->id ? 'active' : '' }}">
                                            {{ $category->name }}
                                            <span class="badge bg-primary rounded-pill">{{ $category->products_count ?? 0 }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Brands -->
                            <div class="mb-4">
                                <h6>Brands</h6>
                                <div class="list-group list-group-flush">
                                    @foreach($brands as $brand)
                                        <a href="?brand={{ $brand->id }}" 
                                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center
                                                  {{ request('brand') == $brand->id ? 'active' : '' }}">
                                            {{ $brand->name }}
                                            <span class="badge bg-primary rounded-pill">{{ $brand->products_count ?? 0 }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Price Range -->
                            <div class="mb-4">
                                <h6>Price Range</h6>
                                <div class="price-range">
                                    <input type="range" class="form-range" id="priceRange" min="0" max="1000" step="10">
                                    <div class="d-flex justify-content-between">
                                        <span>$0</span>
                                        <span id="priceValue">$500</span>
                                        <span>$1000</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Clear Filters -->
                            <div class="text-center">
                                <a href="{{ route('user.shop') }}" class="btn btn-outline-secondary btn-sm">Clear Filters</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9">
                <!-- Products Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="mb-0">Products ({{ $products->total() }})</h5>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm" id="gridView">
                            <i class="icon-grid"></i> Grid
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" id="listView">
                            <i class="icon-list"></i> List
                        </button>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="products-grid row row-cols-1 row-cols-md-2 row-cols-lg-3" id="products-grid">
                    @foreach ($products as $product)
                    <div class="product-card-wrapper col mb-4">
                        <div class="product-card h-100 border rounded shadow-sm">
                            <div class="pc__img-wrapper position-relative">
                                <div class="swiper-container background-img js-swiper-slider" data-settings='{"resizeObserver": true}'>
                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide">
                                            <a href="#" class="d-block">
                                                @if($product->image)
                                                    <img loading="lazy" src="{{asset('uploads/products/thumbnails/'.$product->image)}}" 
                                                         width="330" height="400" alt="{{$product->name}}" class="pc__img w-100 h-100 object-fit-cover">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center" style="width: 330px; height: 400px;">
                                                        <i class="icon-image text-muted" style="font-size: 3rem;"></i>
                                                    </div>
                                                @endif
                                            </a>
                                        </div>
                                        @if($product->images)
                                            @foreach (explode(',', $product->images) as $gimg)
                                                @if($gimg)
                                                    <div class="swiper-slide">
                                                        <a href="#" class="d-block">
                                                            <img loading="lazy" src="{{asset('uploads/products/thumbnails/'.trim($gimg))}}" 
                                                                 width="330" height="400" alt="{{$product->name}}" class="pc__img w-100 h-100 object-fit-cover">
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                    <span class="pc__img-prev">
                                        <svg width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg">
                                            <use href="#icon_prev_sm" />
                                        </svg>
                                    </span>
                                    <span class="pc__img-next">
                                        <svg width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg">
                                            <use href="#icon_next_sm" />
                                        </svg>
                                    </span>
                                </div>
                                
                                <!-- Product Labels -->
                                <div class="pc-labels position-absolute top-0 start-0 p-2">
                                    @if($product->featured == 1)
                                        <span class="pc-label pc-label_new me-2">Featured</span>
                                    @endif
                                    @if($product->stock_status == 'out_of_stock')
                                        <span class="pc-label bg-danger text-white">Out of Stock</span>
                                    @endif
                                </div>

                                <!-- Wishlist Button -->
                                <div class="position-absolute top-0 end-0 p-2">
                                    <button class="btn btn-sm btn-outline-danger wishlist-btn" 
                                            title="Add to Wishlist" data-product-id="{{ $product->id }}">
                                        <i class="icon-heart"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="pc__info p-3">
                                <p class="pc__category text-primary mb-1">{{$product->category->name ?? 'N/A'}}</p>
                                <h6 class="pc__title mb-2">
                                    <a href="#" class="text-decoration-none text-dark">{{$product->name}}</a>
                                </h6>
                                
                                <div class="product-card__price d-flex align-items-center mb-2">
                                    @if($product->sale_price && $product->sale_price < $product->regular_price)
                                        <span class="text-decoration-line-through text-muted me-2">${{$product->regular_price}}</span>
                                        <span class="text-success fw-bold">${{$product->sale_price}}</span>
                                        <span class="badge bg-success ms-2">{{round(($product->regular_price - $product->sale_price)*100/$product->regular_price)}}% OFF</span>
                                    @else
                                        <span class="fw-bold">${{$product->regular_price}}</span>
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
                                
                                <!-- Add to Cart Button -->
                                <div class="d-grid">
                                    @if($product->stock_status == 'out_of_stock')
                                        <button class="btn btn-secondary" disabled>Out of Stock</button>
                                    @else
                                        <button class="btn btn-primary add-to-cart-btn" 
                                                data-product-id="{{ $product->id }}"
                                                data-product-name="{{ $product->name }}"
                                                data-product-price="{{ $product->sale_price ?: $product->regular_price }}">
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
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
    $(function(){
        // Price range slider
        $('#priceRange').on('input', function() {
            $('#priceValue').text('$' + $(this).val());
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
            
            // Toggle wishlist state
            if (btn.hasClass('active')) {
                btn.removeClass('active btn-danger').addClass('btn-outline-danger');
                btn.find('i').removeClass('icon-heart-fill').addClass('icon-heart');
                btn.attr('title', 'Add to Wishlist');
            } else {
                btn.addClass('active btn-danger').removeClass('btn-outline-danger');
                btn.find('i').removeClass('icon-heart').addClass('icon-heart-fill');
                btn.attr('title', 'Remove from Wishlist');
            }
            
            // You can implement AJAX call here to save to wishlist
            console.log('Wishlist toggle for product:', productId);
        });

        // Add to cart functionality
        $('.add-to-cart-btn').on('click', function() {
            const productId = $(this).data('product-id');
            const productName = $(this).data('product-name');
            const productPrice = $(this).data('product-price');
            
            // You can implement AJAX call here to add to cart
            console.log('Add to cart:', { productId, productName, productPrice });
            
            // Show success message
            swal({
                title: "Added to Cart!",
                text: productName + " has been added to your cart",
                type: "success",
                timer: 2000,
                showConfirmButton: false
            });
        });

        // Search functionality
        $('.form-search').on('submit', function(e) {
            e.preventDefault();
            const searchInput = $(this).find('input[name="search"]');
            if (searchInput.val().trim() !== '') {
                // You can implement AJAX search here or let the form submit naturally
                this.submit();
            }
        });
    });
</script>
@endpush
