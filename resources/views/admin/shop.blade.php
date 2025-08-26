@extends('layouts.admin')

@section('content')
<style>
        /* Quick Stats Styling - Larger and More Prominent */
        .quick-stats {
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 25px;
            padding: 3rem;
            margin-bottom: 3rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }
        
        .quick-stats .center-heading {
            font-size: 2.5rem;
            font-weight: 900;
            color: #212529;
            text-align: center;
            margin-bottom: 2.5rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        
        .quick-stats .stat-item {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 2.5rem 2rem;
            border-radius: 20px;
            border: 2px solid #dee2e6;
            text-align: center;
            margin-bottom: 2rem;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .quick-stats .stat-item:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.15);
            border-color: #212529;
        }
        
        .quick-stats .stat-icon {
            width: 80px;
            height: 80px;
            background: #212529;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            transition: all 0.3s ease;
        }
        
        .quick-stats .stat-icon i {
            font-size: 2.5rem;
            color: white;
        }
        
        .quick-stats .stat-item:hover .stat-icon {
            background: #495057;
            transform: scale(1.1);
        }
        
        .quick-stats .stat-number {
            font-size: 3.5rem;
            font-weight: 900;
            color: #212529;
            margin-bottom: 0.75rem;
            line-height: 1;
        }
        
        .quick-stats .stat-label {
            font-size: 1.3rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            font-weight: 700;
        }
        
    .shop-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 4rem;
        border-radius: 25px;
        margin-bottom: 4rem;
        border: 2px solid #dee2e6;
    }
    
    .page-title {
        font-size: 4.5rem;
        font-weight: 900;
        color: #212529;
        margin-bottom: 1.5rem;
        line-height: 1.1;
    }
    
    .page-subtitle {
        color: #6c757d;
        font-size: 1.8rem;
        margin-bottom: 0;
        font-weight: 500;
    }
    

    
    .view-mode-toggle {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
    
    .view-mode-btn {
        padding: 0.75rem 1.5rem;
        border: 2px solid #dee2e6;
        background: white;
        color: #6c757d;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        min-width: 100px;
    }
    
    .view-mode-btn.active {
        border-color: #212529;
        background: #212529;
        color: white;
        transform: scale(1.05);
    }
    
    .view-mode-btn:hover {
        border-color: #212529;
        color: #212529;
        transform: translateY(-1px);
    }
    
    .filters-section {
        background: white;
        border: 2px solid #dee2e6;
        border-radius: 25px;
        padding: 3rem;
        margin-bottom: 4rem;
        box-shadow: 0 6px 25px rgba(0,0,0,0.1);
    }
    
    .filter-group {
        margin-bottom: 2.5rem;
    }
    
    .filter-label {
        font-weight: 800;
        color: #212529;
        margin-bottom: 1rem;
        display: block;
        font-size: 1.3rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .form-control, .form-select {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        font-size: 1rem;
        transition: border-color 0.3s ease;
        height: auto;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #212529;
        box-shadow: 0 0 0 0.3rem rgba(33, 37, 41, 0.15);
    }
    
    .btn {
        border-radius: 12px;
        font-weight: 600;
        padding: 1rem 2rem;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        font-size: 1rem;
    }
    
    .btn-primary {
        background: #212529;
        border-color: #212529;
        color: white;
    }
    
    .btn-primary:hover {
        background: #000;
        border-color: #000;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    
    .btn-outline-secondary {
        border-color: #6c757d;
        color: #6c757d;
        background: white;
    }
    
    .btn-outline-secondary:hover {
        background: #6c757d;
        border-color: #6c757d;
        color: white;
        transform: translateY(-1px);
    }
    
    .shop-sidebar {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 20px;
        padding: 2rem;
        height: fit-content;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    }
    
    .sidebar-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #212529;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 3px solid #e9ecef;
    }
    
    .list-group-item {
        border: none;
        border-bottom: 1px solid #f8f9fa;
        padding: 1rem 0;
        color: #495057;
        transition: all 0.3s ease;
        background: transparent;
        font-size: 1rem;
    }
    
    .list-group-item:hover {
        background: #f8f9fa;
        color: #212529;
        transform: translateX(8px);
    }
    
    .list-group-item.active {
        background: #212529;
        color: white;
        border-color: #212529;
    }
    
    .badge {
        font-size: 0.8rem;
        padding: 0.5rem 0.8rem;
        border-radius: 25px;
        font-weight: 700;
    }
    
    .badge.bg-primary {
        background: #212529 !important;
    }
    
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 2rem;
    }
    
    .product-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    }
    
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 35px rgba(0,0,0,0.15);
        border-color: #212529;
    }
    
    .product-image {
        position: relative;
        height: 250px;
        overflow: hidden;
        background: #f8f9fa;
    }
    
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .product-card:hover .product-image img {
        transform: scale(1.08);
    }
    
    .product-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: #212529;
        color: white;
        padding: 0.4rem 1rem;
        border-radius: 25px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }
    
    .product-info {
        padding: 2rem;
    }
    
    .product-category {
        color: #6c757d;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 0.75rem;
        font-weight: 600;
    }
    
    .product-name {
        font-size: 1.3rem;
        font-weight: 800;
        color: #212529;
        margin-bottom: 1rem;
        line-height: 1.4;
    }
    
    .product-price {
        font-size: 1.4rem;
        font-weight: 800;
        color: #212529;
        margin-bottom: 1.5rem;
    }
    
    .product-actions {
        display: flex;
        gap: 0.75rem;
    }
    
    .btn-sm {
        padding: 0.75rem 1.25rem;
        font-size: 0.9rem;
        border-radius: 10px;
        font-weight: 600;
    }
    
    .btn-outline-primary {
        border-color: #212529;
        color: #212529;
        background: white;
    }
    
    .btn-outline-primary:hover {
        background: #212529;
        border-color: #212529;
        color: white;
        transform: translateY(-1px);
    }
    
    .btn-outline-warning {
        border-color: #ffc107;
        color: #ffc107;
        background: white;
    }
    
    .btn-outline-warning:hover {
        background: #ffc107;
        border-color: #ffc107;
        color: #212529;
        transform: translateY(-1px);
    }
    
    .btn-outline-danger {
        border-color: #dc3545;
        color: #dc3545;
        background: white;
    }
    
    .btn-outline-danger:hover {
        background: #dc3545;
        border-color: #dc3545;
        color: white;
        transform: translateY(-1px);
    }
    
    .no-products {
        text-align: center;
        padding: 4rem 2rem;
        color: #6c757d;
    }
    
    .no-products i {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        opacity: 0.3;
    }
    
    .pagination {
        margin-top: 3rem;
        justify-content: center;
    }
    
    .page-link {
        border: 2px solid #dee2e6;
        color: #212529;
        padding: 1rem 1.25rem;
        margin: 0 0.5rem;
        border-radius: 12px;
        transition: all 0.3s ease;
        font-weight: 600;
    }
    
    .page-link:hover {
        background: #212529;
        border-color: #212529;
        color: white;
        transform: translateY(-1px);
    }
    
    .page-item.active .page-link {
        background: #212529;
        border-color: #212529;
        color: white;
        transform: scale(1.05);
    }
</style>

<div class="section-content-right">
    <!-- Shop Header -->
    <section class="shop-header">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="page-title">Shop</h1>
                <p class="page-subtitle">Manage and view all products in your store</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="view-mode-toggle">
                    <button class="view-mode-btn active" onclick="setViewMode('grid')">
                        <i class="icon-grid"></i> Grid
                    </button>
                    <button class="view-mode-btn" onclick="setViewMode('list')">
                        <i class="icon-list"></i> List
                    </button>
                </div>
            </div>
        </div>


    </section>

    <!-- Filters Section -->
    <div class="filters-section">
        <form method="GET" action="{{ route('admin.shop') }}" id="filterForm">
            <div class="row">
                <div class="col-md-3">
                    <div class="filter-group">
                        <label class="filter-label">Search</label>
                        <input type="text" class="form-control" name="search" placeholder="Search products..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="filter-group">
                        <label class="filter-label">Category</label>
                        <select class="form-select" name="category" id="categoryFilter">
                            <option value="">All Categories ({{ $shopStats['total_products'] }})</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ $category->products_count }})
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
                                    {{ $brand->name }} ({{ $brand->products_count }})
                                </option>
                            @endforeach
                        </select>
                        </div>
                </div>
                <div class="col-md-3">
                    <div class="filter-group">
                        <label class="filter-label">Actions</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="icon-search"></i> Apply Filters
                            </button>
                            <a href="{{ route('admin.shop') }}" class="btn btn-outline-secondary">
                                <i class="icon-refresh"></i> Clear
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        </div>

    <div class="row">


        <!-- Products Grid -->
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">Products ({{ $products->count() }})</h5>
                <a href="{{ route('admin.product.add') }}" class="btn btn-primary">
                    <i class="icon-plus"></i> Add Product
                </a>
            </div>

            @if($products->count() > 0)
                <div class="product-grid" id="productsGrid">
                    @foreach($products as $product)
                    <div class="product-card">
                        <div class="product-image">
                                @if($product->image)
                                <img src="{{ asset('uploads/products/thumbnails/'.$product->image) }}" 
                                     alt="{{ $product->name }}">
                                @else
                                <div class="d-flex align-items-center justify-content-center h-100">
                                        <i class="icon-image text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                            @if($product->featured)
                                <div class="product-badge">Featured</div>
                                @endif
                            </div>
                        <div class="product-info">
                            <div class="product-category">{{ $product->category->name ?? 'N/A' }}</div>
                            <h6 class="product-name">{{ $product->name }}</h6>
                            <div class="product-price">
                                @if($product->sale_price && $product->sale_price < $product->regular_price)
                                    <span class="text-danger">${{ number_format($product->sale_price, 2) }}</span>
                                    <small class="text-muted text-decoration-line-through ms-2">${{ number_format($product->regular_price, 2) }}</small>
                                @else
                                    ${{ number_format($product->regular_price, 2) }}
                                @endif
                            </div>
                            <div class="product-actions">
                                <a href="{{ route('admin.product.details', $product->id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="icon-eye"></i> View
                                </a>
                                <a href="{{ route('admin.product.edit', $product->id) }}" 
                                   class="btn btn-sm btn-outline-warning">
                                    <i class="icon-edit"></i> Edit
                                </a>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteProduct({{ $product->id }}, '{{ $product->name }}')">
                                    <i class="icon-trash"></i> Delete
                                    </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                            </div>
                            
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
                                </div>
            @else
                <div class="no-products">
                    <i class="icon-package"></i>
                    <h5>No products found</h5>
                    <p class="text-muted">Try adjusting your filters or add some products to get started.</p>
                    <a href="{{ route('admin.product.add') }}" class="btn btn-primary">
                        <i class="icon-plus"></i> Add Your First Product
                    </a>
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

@push('scripts')
<script>
    // Debug route generation
    console.log('Admin shop page loaded');
    console.log('Delete route template:', '{{ route("admin.product.delete", ":id") }}');
    
    function setViewMode(mode) {
        // Remove active class from all buttons
        document.querySelectorAll('.view-mode-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Add active class to clicked button
        event.target.classList.add('active');
        
        // Store preference in localStorage
        localStorage.setItem('adminShopViewMode', mode);
        
        // Apply view mode changes if needed
        if (mode === 'list') {
            document.getElementById('productsGrid').style.gridTemplateColumns = '1fr';
        } else {
            document.getElementById('productsGrid').style.gridTemplateColumns = 'repeat(auto-fill, minmax(280px, 1fr))';
        }
    }
    
    function deleteProduct(productId, productName) {
        try {
            console.log('deleteProduct called with:', { productId, productName });
            
            // Set modal content
            const productNameElement = document.getElementById('productNameToDelete');
            const deleteForm = document.getElementById('deleteForm');
            
            if (!productNameElement || !deleteForm) {
                console.error('Required elements not found:', { productNameElement, deleteForm });
                alert('Error: Required elements not found. Please refresh the page and try again.');
                return;
            }
            
            productNameElement.textContent = productName;
            
            // Construct the delete URL properly
            const deleteUrl = '{{ route("admin.product.delete", ":id") }}'.replace(':id', productId);
            deleteForm.action = deleteUrl;
            
            console.log('Delete URL set to:', deleteUrl);
            
            // Show modal using Bootstrap 5
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
            
        } catch (error) {
            console.error('Error in deleteProduct:', error);
            alert('An error occurred while setting up the delete modal. Please try again.');
        }
    }
    
    // Load saved view mode preference
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing admin shop');
        
        const savedMode = localStorage.getItem('adminShopViewMode') || 'grid';
        setViewMode(savedMode);
        
        // Set the correct button as active
        const buttons = document.querySelectorAll('.view-mode-btn');
        buttons.forEach(btn => {
            btn.classList.remove('active');
            if (btn.textContent.toLowerCase().includes(savedMode)) {
                btn.classList.add('active');
            }
        });
        
            // Test modal elements
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    console.log('Modal elements found:', { modal: !!modal, form: !!form });
    
    // Test if Bootstrap is available
    console.log('Bootstrap available:', typeof bootstrap !== 'undefined');
    if (typeof bootstrap !== 'undefined') {
        console.log('Bootstrap Modal available:', typeof bootstrap.Modal !== 'undefined');
    }
    
    // Test modal manually
    const testModal = () => {
        try {
            const testModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            testModal.show();
            console.log('Test modal shown successfully');
        } catch (error) {
            console.error('Error showing test modal:', error);
        }
    };
    
    // Add test button to page
    const testButton = document.createElement('button');
    testButton.textContent = 'Test Modal';
    testButton.className = 'btn btn-warning btn-sm';
    testButton.onclick = testModal;
    testButton.style.position = 'fixed';
    testButton.style.top = '10px';
    testButton.style.right = '10px';
    testButton.style.zIndex = '9999';
    document.body.appendChild(testButton);
    });
</script>
@endpush
@endsection
