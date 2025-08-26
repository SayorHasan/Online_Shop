@extends('layouts.admin')

@section('content')
<style>
    /* Shop View Design for Product Details */
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
    
    .product-details-section {
        background: white;
        border: 2px solid #dee2e6;
        border-radius: 25px;
        padding: 3rem;
        margin-bottom: 3rem;
        box-shadow: 0 6px 25px rgba(0,0,0,0.1);
    }
    
    .product-image-container {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }
    
    .main-image-wrapper {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        background: #f8f9fa;
    }
    
    .main-image {
        width: 100%;
        height: 500px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .main-image:hover {
        transform: scale(1.05);
    }
    
    .product-info-card {
        background: white;
        border: 2px solid #dee2e6;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #f8f9fa;
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 700;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-size: 0.9rem;
    }
    
    .info-value {
        font-weight: 600;
        color: #212529;
        font-size: 1.1rem;
    }
    
    .btn {
        border-radius: 12px;
        font-weight: 600;
        padding: 1rem 2rem;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        font-size: 1rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
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
        color: white;
    }
    
    .btn-warning {
        background: #ffc107;
        border-color: #ffc107;
        color: #212529;
    }
    
    .btn-warning:hover {
        background: #e0a800;
        border-color: #e0a800;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        color: #212529;
    }
    
    .btn-danger {
        background: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    
    .btn-danger:hover {
        background: #c82333;
        border-color: #c82333;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        color: white;
    }
    
    .btn-info {
        background: #17a2b8;
        border-color: #17a2b8;
        color: white;
    }
    
    .btn-info:hover {
        background: #138496;
        border-color: #138496;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        color: white;
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
    
    .quick-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 2rem;
    }
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }
    
    .status-in_stock {
        background: #d4edda;
        color: #155724;
    }
    
    .status-out_of_stock {
        background: #f8d7da;
        color: #721c24;
    }
    
    .status-featured {
        background: #fff3cd;
        color: #856404;
    }
    
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }
    
    .gallery-item {
        position: relative;
        border-radius: 15px;
        overflow: hidden;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .gallery-item:hover {
        border-color: #212529;
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .gallery-image {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }
</style>

<div class="container-fluid">
    <!-- Shop Header -->
    <section class="shop-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="page-title">Product Details</h1>
                <p class="page-subtitle">View detailed information about {{ $product->name }}</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary">
                    <i class="icon-arrow-left me-2"></i>Back to Products
                </a>
            </div>
        </div>
    </section>

    <!-- Product Details Section -->
    <div class="product-details-section">
        <div class="row">
            <!-- Product Images -->
            <div class="col-lg-6 mb-4">
                <div class="product-image-container">
                    @if($product->image)
                        <div class="main-image-wrapper">
                            <img src="{{ asset('uploads/products/'.$product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="main-image">
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="icon-image text-muted" style="font-size: 4rem;"></i>
                            <p class="mt-3">No main image available</p>
                        </div>
                    @endif
                </div>
                
                @if($product->images && count($product->gallery) > 0)
                    <h6 class="mt-4 mb-3">Gallery Images</h6>
                    <div class="gallery-grid">
                        @foreach($product->gallery as $galleryImage)
                        <div class="gallery-item">
                            <img src="{{ asset('uploads/products/thumbnails/'.$galleryImage) }}" 
                                 alt="{{ $product->name }}" 
                                 class="gallery-image">
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Information -->
            <div class="col-lg-6">
                <div class="product-info-card">
                    <h2 class="mb-4">{{ $product->name }}</h2>
                    
                    <div class="info-row">
                        <span class="info-label">Category</span>
                        <span class="info-value">{{ $product->category->name ?? 'N/A' }}</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Brand</span>
                        <span class="info-value">{{ $product->brand->name ?? 'N/A' }}</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">SKU</span>
                        <span class="info-value">{{ $product->SKU }}</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Regular Price</span>
                        <span class="info-value">${{ number_format($product->regular_price, 2) }}</span>
                    </div>
                    
                    @if($product->sale_price && $product->sale_price < $product->regular_price)
                    <div class="info-row">
                        <span class="info-label">Sale Price</span>
                        <span class="info-value text-danger">${{ number_format($product->sale_price, 2) }}</span>
                    </div>
                    @endif
                    
                    <div class="info-row">
                        <span class="info-label">Stock Status</span>
                        <span class="status-badge status-{{ $product->stock_status }}">
                            {{ ucfirst(str_replace('_', ' ', $product->stock_status)) }}
                        </span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Quantity</span>
                        <span class="info-value">{{ $product->quantity }}</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Featured</span>
                        <span class="info-value">{{ $product->featured ? 'Yes' : 'No' }}</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Description</span>
                        <span class="info-value">{{ $product->description }}</span>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="quick-actions">
                        <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-warning">
                            <i class="icon-edit"></i>Edit Product
                        </a>
                        <a href="{{ route('user.shop') }}?category={{ $product->category_id }}&highlight={{ $product->id }}" 
                           target="_blank" class="btn btn-info">
                            <i class="icon-external-link"></i>View in Shop
                        </a>
                        <button class="btn btn-danger" onclick="deleteProduct()">
                            <i class="icon-trash"></i>Delete Product
                        </button>
                    </div>
                </div>
            </div>
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
    function deleteProduct() {
        // Set modal content
        document.getElementById('productNameToDelete').textContent = '{{ $product->name }}';
        document.getElementById('deleteForm').action = '{{ route("admin.product.delete", $product->id) }}';
        
        // Show modal using Bootstrap 5
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
</script>
@endpush

@endsection
