@extends('layouts.admin')
@section('content')
<style>
    .shop-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
    }
    .shop-stats {
        display: flex;
        gap: 20px;
        margin-top: 20px;
    }
    .stat-item {
        text-align: center;
        flex: 1;
    }
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 5px;
    }
    .stat-label {
        font-size: 14px;
        opacity: 0.9;
    }
    .filters-section {
        background: #f8f9fa;
        padding: 25px;
        border-radius: 10px;
        margin-bottom: 30px;
        border: 1px solid #e9ecef;
    }
    .filter-group {
        margin-bottom: 15px;
    }
    .filter-label {
        font-weight: 600;
        margin-bottom: 8px;
        color: #495057;
    }
    .product-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        border-color: #007bff;
    }
    .product-image-container {
        position: relative;
        height: 250px;
        overflow: hidden;
        background: #f8f9fa;
    }
    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .product-card:hover .product-image {
        transform: scale(1.05);
    }
    .product-labels {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 2;
    }
    .product-label {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 5px;
        margin-right: 5px;
    }
    .label-featured { background: #ffc107; color: #212529; }
    .label-outofstock { background: #dc3545; color: white; }
    .label-lowstock { background: #fd7e14; color: white; }
    .admin-actions {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 2;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    .btn-admin {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        font-size: 14px;
    }
    .btn-view { background: #17a2b8; color: white; }
    .btn-view:hover { background: #138496; color: white; }
    .btn-edit { background: #ffc107; color: #212529; }
    .btn-edit:hover { background: #e0a800; color: #212529; }
    .btn-delete { background: #dc3545; color: white; }
    .btn-delete:hover { background: #c82333; color: white; }
    .product-info {
        padding: 20px;
    }
    .product-category {
        color: #007bff;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    .product-title {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
        line-height: 1.4;
        text-decoration: none;
    }
    .product-title:hover {
        color: #007bff;
    }
    .product-price {
        margin-bottom: 15px;
    }
    .price-regular {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
    }
    .price-sale {
        font-size: 20px;
        font-weight: 700;
        color: #dc3545;
        margin-left: 10px;
    }
    .price-original {
        text-decoration: line-through;
        color: #6c757d;
        font-size: 14px;
    }
    .discount-badge {
        background: #28a745;
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        margin-left: 10px;
    }
    .product-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        font-size: 12px;
        color: #6c757d;
    }
    .product-rating {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 15px;
    }
    .rating-stars {
        color: #ffc107;
    }
    .rating-text {
        font-size: 12px;
        color: #6c757d;
    }
    .view-mode-toggle {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }
    .btn-view-mode {
        padding: 8px 16px;
        border: 1px solid #dee2e6;
        background: white;
        color: #6c757d;
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .btn-view-mode.active {
        background: #007bff;
        color: white;
        border-color: #007bff;
    }
    .btn-view-mode:hover {
        background: #f8f9fa;
        color: #495057;
    }
    .btn-view-mode.active:hover {
        background: #0056b3;
        color: white;
    }
    .no-products {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }
    .no-products i {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.3;
    }
</style>

<div class="main-content-inner">
    <div class="main-content-wrap">
        <!-- Shop Header -->
        <div class="shop-header">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h2 class="mb-2">Product Catalog</h2>
                    <p class="mb-0 opacity-75">Browse and manage your product inventory in a visual grid layout</p>
                </div>
                <a href="{{ route('admin.product.add') }}" class="btn btn-light">
                    <i class="icon-plus me-2"></i>Add Product
                </a>
            </div>
            
            <div class="shop-stats">
                <div class="stat-item">
                    <div class="stat-number">{{ $products->total() }}</div>
                    <div class="stat-label">Total Products</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $products->where('featured', 1)->count() }}</div>
                    <div class="stat-label">Featured</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $products->where('stock_status', 'in_stock')->count() }}</div>
                    <div class="stat-label">In Stock</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $products->where('stock_status', 'out_of_stock')->count() }}</div>
                    <div class="stat-label">Out of Stock</div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="filters-section">
            <form method="GET" action="{{ route('admin.shop') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-4">
                        <div class="filter-group">
                            <label class="filter-label">Search Products</label>
                            <div class="input-group">
                                <input type="text" placeholder="Search by name or SKU..." 
                                       class="form-control" name="search" value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="icon-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="filter-group">
                            <label class="filter-label">Category</label>
                            <select class="form-select" name="category" id="categoryFilter">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="filter-group">
                            <label class="filter-label">Brand</label>
                            <select class="form-select" name="brand" id="brandFilter">
                                <option value="">All Brands</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="filter-group">
                            <label class="filter-label">Stock Status</label>
                            <select class="form-select" name="stock" id="stockFilter">
                                <option value="">All</option>
                                <option value="in_stock" {{ request('stock') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                <option value="out_of_stock" {{ request('stock') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="d-flex gap-2 filter-buttons">
                            <button type="submit" class="btn btn-primary">
                                <i class="icon-filter me-2"></i>Apply Filters
                            </button>
                            <a href="{{ route('admin.shop') }}" class="btn btn-outline-secondary">
                                <i class="icon-refresh me-2"></i>Clear Filters
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- View Mode Toggle -->
        <div class="view-mode-toggle">
            <button class="btn-view-mode active" data-view="grid">
                <i class="icon-grid me-2"></i>Grid View
            </button>
            <button class="btn-view-mode" data-view="list">
                <i class="icon-list me-2"></i>List View
            </button>
        </div>

        <!-- Products Grid -->
        <div class="wg-box">
            @if($products->count() > 0)
                <div class="products-grid row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4" id="products-grid">
                    @foreach ($products as $product)
                    <div class="product-card-wrapper col mb-4">
                        <div class="product-card">
                            <div class="product-image-container">
                                @if($product->image)
                                    <img loading="lazy" src="{{asset('uploads/products/thumbnails/'.$product->image)}}" 
                                         alt="{{$product->name}}" class="product-image">
                                @else
                                    <div class="d-flex align-items-center justify-content-center h-100">
                                        <i class="icon-image text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                                
                                <!-- Product Labels -->
                                <div class="product-labels">
                                    @if($product->featured == 1)
                                        <span class="product-label label-featured">Featured</span>
                                    @endif
                                    @if($product->stock_status == 'out_of_stock')
                                        <span class="product-label label-outofstock">Out of Stock</span>
                                    @elseif($product->quantity <= 5 && $product->quantity > 0)
                                        <span class="product-label label-lowstock">Low Stock</span>
                                    @endif
                                </div>

                                <!-- Admin Actions -->
                                <div class="admin-actions">
                                    <a href="{{ route('admin.product.details', $product->id) }}" 
                                       class="btn-admin btn-view" title="View Details">
                                        <i class="icon-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.product.edit', $product->id) }}" 
                                       class="btn-admin btn-edit" title="Edit Product">
                                        <i class="icon-edit-3"></i>
                                    </a>
                                    <button type="button" class="btn-admin btn-delete delete-product" 
                                            title="Delete Product" 
                                            data-product-id="{{ $product->id }}"
                                            data-product-name="{{ $product->name }}">
                                        <i class="icon-trash-2"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="product-info">
                                <div class="product-category">{{$product->category->name ?? 'N/A'}}</div>
                                <h6 class="product-title">
                                    <a href="{{ route('admin.product.details', $product->id) }}">{{$product->name}}</a>
                                </h6>
                                
                                <div class="product-price">
                                    @if($product->sale_price && $product->sale_price < $product->regular_price)
                                        <span class="price-sale">${{number_format($product->sale_price, 2)}}</span>
                                        <span class="price-original">${{number_format($product->regular_price, 2)}}</span>
                                        <span class="discount-badge">
                                            {{round(($product->regular_price - $product->sale_price)*100/$product->regular_price)}}% OFF
                                        </span>
                                    @else
                                        <span class="price-regular">${{number_format($product->regular_price, 2)}}</span>
                                    @endif
                                </div>
                                
                                <div class="product-rating">
                                    <div class="rating-stars">
                                        <i class="icon-star"></i>
                                        <i class="icon-star"></i>
                                        <i class="icon-star"></i>
                                        <i class="icon-star"></i>
                                        <i class="icon-star"></i>
                                    </div>
                                    <span class="rating-text">5.0 ({{ rand(10, 100) }}+ reviews)</span>
                                </div>
                                
                                <div class="product-meta">
                                    <span>SKU: {{$product->SKU}}</span>
                                    <span>Qty: {{$product->quantity}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="no-products">
                    <i class="icon-package"></i>
                    <h4>No Products Found</h4>
                    <p>No products match your current filters. Try adjusting your search criteria or add new products.</p>
                    <a href="{{ route('admin.product.add') }}" class="btn btn-primary">
                        <i class="icon-plus me-2"></i>Add Your First Product
                    </a>
                </div>
            @endif
            
            <!-- Pagination -->
            @if($products->count() > 0)
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{$products->withQueryString()->links('pagination::bootstrap-5')}}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="productNameToDelete"></strong>?</p>
                <p class="text-danger"><small>This action cannot be undone and will permanently remove the product and all associated images.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Product</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function(){
        // Delete product functionality
        $('.delete-product').on('click', function(e) {
            e.preventDefault();
            const productId = $(this).data('product-id');
            const productName = $(this).data('product-name');
            
            // Set modal content
            $('#productNameToDelete').text(productName);
            $('#deleteForm').attr('action', '{{ route("admin.product.delete", ":id") }}'.replace(':id', productId));
            
            // Show modal
            $('#deleteModal').modal('show');
        });

        // View mode toggle
        $('.btn-view-mode').on('click', function() {
            $('.btn-view-mode').removeClass('active');
            $(this).addClass('active');
            
            const viewMode = $(this).data('view');
            if (viewMode === 'list') {
                $('#products-grid').removeClass('row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4')
                                .addClass('row-cols-1');
            } else {
                $('#products-grid').removeClass('row-cols-1')
                                .addClass('row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4');
            }
        });

        // Search functionality
        $('.form-search').on('submit', function(e) {
            const searchInput = $(this).find('input[name="search"]');
            if (searchInput.val().trim() === '') {
                e.preventDefault();
            }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    });
</script>
@endpush
