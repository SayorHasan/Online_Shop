@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
      <h2 class="page-title">My Account</h2>
      <div class="row">
        <div class="col-lg-3">
          @include("user.account-nav")
        </div>
        <div class="col-lg-9">
          <div class="page-content my-account__dashboard">
            <div class="welcome-section mb-4">
                <h3>Hello <strong>{{ $user->name }}</strong>!</h3>
                <p class="text-muted">Welcome to your account dashboard. Here's what's happening in our store.</p>
            </div>
            
            <!-- Quick Stats -->
            <div class="stats-section mb-4">
                <h4>Store Overview</h4>
                <div class="row">
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-card text-center p-3 bg-primary text-white rounded">
                            <div class="stat-number h4">{{ $totalProducts }}</div>
                            <div class="stat-label">Products</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-card text-center p-3 bg-success text-white rounded">
                            <div class="stat-number h4">{{ $totalCategories }}</div>
                            <div class="stat-label">Categories</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-card text-center p-3 bg-info text-white rounded">
                            <div class="stat-number h4">{{ $totalBrands }}</div>
                            <div class="stat-label">Brands</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-card text-center p-3 bg-warning text-white rounded">
                            <div class="stat-number h4">{{ $featuredProducts->count() }}</div>
                            <div class="stat-label">Featured</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Featured Products -->
            @if($featuredProducts->count() > 0)
            <div class="featured-products mb-4">
                <h4>Featured Products</h4>
                <div class="row">
                    @foreach($featuredProducts as $product)
                    <div class="col-md-4 col-6 mb-3">
                        <div class="product-card border rounded p-2">
                            <div class="product-image mb-2">
                                @if($product->image)
                                    <img src="{{ asset('uploads/products/thumbnails/'.$product->image) }}" 
                                         alt="{{ $product->name }}" class="img-fluid rounded">
                                @else
                                    <div class="no-image bg-light text-center py-4 rounded">
                                        <i class="icon-image text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="product-info">
                                <h6 class="product-name mb-1">{{ $product->name }}</h6>
                                <div class="product-category text-muted small mb-1">
                                    {{ $product->category->name ?? 'N/A' }}
                                </div>
                                <div class="product-price">
                                    <span class="text-primary fw-bold">${{ $product->sale_price }}</span>
                                    @if($product->regular_price > $product->sale_price)
                                        <small class="text-muted text-decoration-line-through">${{ $product->regular_price }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            <!-- Latest Products -->
            @if($latestProducts->count() > 0)
            <div class="latest-products mb-4">
                <h4>Latest Products</h4>
                <div class="row">
                    @foreach($latestProducts as $product)
                    <div class="col-md-4 col-6 mb-3">
                        <div class="product-card border rounded p-2">
                            <div class="product-image mb-2">
                                @if($product->image)
                                    <img src="{{ asset('uploads/products/thumbnails/'.$product->image) }}" 
                                         alt="{{ $product->name }}" class="img-fluid rounded">
                                @else
                                    <div class="no-image bg-light text-center py-4 rounded">
                                        <i class="icon-image text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="product-info">
                                <h6 class="product-name mb-1">{{ $product->name }}</h6>
                                <div class="product-category text-muted small mb-1">
                                    {{ $product->category->name ?? 'N/A' }}
                                </div>
                                <div class="product-price">
                                    <span class="text-primary fw-bold">${{ $product->sale_price }}</span>
                                    @if($product->regular_price > $product->sale_price)
                                        <small class="text-muted text-decoration-line-through">${{ $product->regular_price }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            <div class="account-links">
                <p>From your account dashboard you can view your <a class="text-primary" href="account_orders.html">recent orders</a>, manage your <a class="text-primary" href="account_edit_address.html">shipping addresses</a>, and <a class="text-primary" href="account_edit.html">edit your password and account details.</a></p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection