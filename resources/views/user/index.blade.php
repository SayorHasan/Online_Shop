@extends('layouts.app')
@section('content')
<style>
    /* Professional Business-Class Dashboard Styling */
    .dashboard-container {
        background: #ffffff;
        min-height: 100vh;
    }
    
    .dashboard-header {
        color: #1a202c;
        padding: 3rem 0;
        margin-bottom: 2rem;
        position: relative;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .dashboard-header::before {
        display: none;
    }
    
    .dashboard-header .container {
        position: relative;
        z-index: 2;
    }
    
    .dashboard-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #1a202c;
    }
    
    .dashboard-header p {
        font-size: 1.1rem;
        color: #64748b;
        margin-bottom: 0;
    }
    
    .welcome-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.5rem;
        margin-top: 1rem;
    }
    
    .welcome-card h3 {
        color: #1a202c;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }
    
    .welcome-card p {
        color: #64748b;
        margin-bottom: 0;
    }
    
    /* Stats Section */
    .stats-section {
        background: #ffffff;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid #f1f3f4;
    }
    
    .stats-section h4 {
        color: #1a202c;
        font-weight: 700;
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
        text-align: center;
        position: relative;
    }
    
    .stats-section h4::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        border-radius: 2px;
    }
    
    .quick-stats {
        background: transparent;
        border: none;
        padding: 0;
        margin: 0;
    }
    
    .quick-stats .stat-item {
        background: #ffffff;
        color: #1a202c;
        padding: 2rem 1.5rem;
        border-radius: 16px;
        text-align: center;
        margin-bottom: 1rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid #e2e8f0;
        position: relative;
        overflow: hidden;
        height: 100%;
    }
    
    .quick-stats .stat-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #f5576c);
    }
    
    .quick-stats .stat-item:hover {
        transform: translateY(-8px);
        border-color: #667eea;
        background: #f0f4ff;
    }
    
    .quick-stats .stat-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        transition: all 0.4s ease;
        border: 3px solid rgba(102, 126, 234, 0.1);
        position: relative;
    }
    
    .quick-stats .stat-item:hover .stat-icon {
        transform: scale(1.1) rotate(5deg);
        border-color: rgba(102, 126, 234, 0.3);
    }
    
    .quick-stats .stat-icon i {
        font-size: 1.8rem;
        font-weight: 600;
    }
    
    .quick-stats .stat-number {
        font-size: 3rem;
        font-weight: 800;
        color: #1a202c;
        margin-bottom: 0.5rem;
        line-height: 1;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .quick-stats .stat-label {
        font-size: 0.9rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 600;
    }
    
    /* Products Section */
    .products-section {
        background: #ffffff;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid #f1f3f4;
    }
    
    .products-section h4 {
        color: #1a202c;
        font-weight: 700;
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
        position: relative;
        padding-bottom: 0.5rem;
    }
    
    .products-section h4::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        border-radius: 2px;
    }
    
    .product-card {
        background: #ffffff;
        border: 2px solid #f1f3f4;
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    
    .product-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .product-card:hover {
        transform: translateY(-4px);
        border-color: #667eea;
    }
    
    .product-card:hover::before {
        transform: scaleX(1);
    }
    
    .product-image {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1rem;
    }
    
    .product-image img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .product-card:hover .product-image img {
        transform: scale(1.05);
    }
    
    .no-image {
        width: 100%;
        height: 200px;
        background: #f1f3f4;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }
    
    .no-image i {
        font-size: 3rem;
        color: #94a3b8;
    }
    
    .product-info h6 {
        color: #1a202c;
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 1rem;
        line-height: 1.4;
    }
    
    .product-category {
        color: #64748b;
        font-size: 0.85rem;
        margin-bottom: 0.75rem;
        font-weight: 500;
    }
    
    .product-price {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .product-price .text-primary {
        color: #667eea !important;
        font-weight: 700;
        font-size: 1.1rem;
    }
    
    .product-price small {
        color: #94a3b8;
        font-size: 0.85rem;
    }
    
    /* Account Links */
    .account-links {
        background: #f8fafc;
        border-radius: 16px;
        padding: 2rem;
        border: 1px solid #e2e8f0;
    }
    
    .account-links p {
        color: #475569;
        margin-bottom: 0;
        line-height: 1.6;
        font-size: 1rem;
    }
    
    .account-links a {
        color: #667eea !important;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }
    
    .account-links a:hover {
        color: #764ba2 !important;
        text-decoration: underline;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .dashboard-header {
            padding: 2rem 0;
        }
        
        .dashboard-header h1 {
            font-size: 2rem;
        }
        
        .stats-section,
        .products-section {
            padding: 1.5rem;
        }
        
        .quick-stats .stat-item {
            padding: 1.5rem 1rem;
        }
        
        .quick-stats .stat-number {
            font-size: 2.5rem;
        }
        
        .quick-stats .stat-icon {
            width: 60px;
            height: 60px;
        }
        
        .quick-stats .stat-icon i {
            font-size: 1.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .dashboard-header h1 {
            font-size: 1.75rem;
        }
        
        .quick-stats .stat-item {
            padding: 1.25rem 0.75rem;
        }
        
        .quick-stats .stat-number {
            font-size: 2rem;
        }
    }
</style>

<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    
    <!-- Dashboard Header -->
    <section class="dashboard-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1>Welcome Back, {{ $user->name }}!</h1>
                    <p>Your personal dashboard for managing orders, wishlists, and account preferences</p>
                </div>
                <div class="col-lg-4">
                    <div class="welcome-card">
                        <h3>Dashboard Overview</h3>
                        <p>Track your shopping activity and manage your account</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <div class="container">
        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-lg-3">
                @include("user.account-nav")
            </div>
            
            <!-- Main Dashboard Content -->
            <div class="col-lg-9">
                <!-- Quick Stats -->
                <div class="stats-section">
                    <h4>Store Overview</h4>
                    <div class="quick-stats">
                        <div class="row">
                            <div class="col-md-3 col-6 mb-3">
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="icon-shopping-bag"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="stat-number">{{ $totalProducts }}</div>
                                        <div class="stat-label">Products</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="icon-tag"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="stat-number">{{ $totalCategories }}</div>
                                        <div class="stat-label">Categories</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="icon-award"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="stat-number">{{ $totalBrands }}</div>
                                        <div class="stat-label">Brands</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="icon-star"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="stat-number">{{ $featuredProducts->count() }}</div>
                                        <div class="stat-label">Featured</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Featured Products -->
                @if($featuredProducts->count() > 0)
                <div class="products-section">
                    <h4>Featured Products</h4>
                    <div class="row">
                        @foreach($featuredProducts as $product)
                        <div class="col-md-4 col-6 mb-3">
                            <div class="product-card">
                                <div class="product-image">
                                    @if($product->image)
                                        <img src="{{ asset('uploads/products/thumbnails/'.$product->image) }}" 
                                             alt="{{ $product->name }}" class="img-fluid">
                                    @else
                                        <div class="no-image">
                                            <i class="icon-image"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="product-info">
                                    <h6 class="product-name">{{ $product->name }}</h6>
                                    <div class="product-category">
                                        {{ $product->category->name ?? 'N/A' }}
                                    </div>
                                    <div class="product-price">
                                        <span class="text-primary">${{ $product->sale_price }}</span>
                                        @if($product->regular_price > $product->sale_price)
                                            <small class="text-decoration-line-through">${{ $product->regular_price }}</small>
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
                <div class="products-section">
                    <h4>Latest Arrivals</h4>
                    <div class="row">
                        @foreach($latestProducts as $product)
                        <div class="col-md-4 col-6 mb-3">
                            <div class="product-card">
                                <div class="product-image">
                                    @if($product->image)
                                        <img src="{{ asset('uploads/products/thumbnails/'.$product->image) }}" 
                                             alt="{{ $product->name }}" class="img-fluid">
                                    @else
                                        <div class="no-image">
                                            <i class="icon-image"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="product-info">
                                    <h6 class="product-name">{{ $product->name }}</h6>
                                    <div class="product-category">
                                        {{ $product->category->name ?? 'N/A' }}
                                    </div>
                                    <div class="product-price">
                                        <span class="text-primary">${{ $product->sale_price }}</span>
                                        @if($product->regular_price > $product->sale_price)
                                            <small class="text-decoration-line-through">${{ $product->regular_price }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Quick Actions -->
                <div class="account-links">
                    <p>
                        From your account dashboard you can view your 
                        <a href="{{ route('user.orders') }}">recent orders</a>, 
                        manage your <a href="{{ route('user.addresses') }}">shipping addresses</a>, 
                        and <a href="{{ route('user.index') }}">edit your password and account details</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection