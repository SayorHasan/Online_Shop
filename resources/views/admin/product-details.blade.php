@extends('layouts.admin')

@section('content')
<style>
    .product-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 25px;
    }
    .product-stats {
        display: flex;
        gap: 20px;
        margin-top: 20px;
    }
    .stat-item {
        text-align: center;
        flex: 1;
        background: rgba(255,255,255,0.1);
        padding: 15px;
        border-radius: 8px;
    }
    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 5px;
    }
    .stat-label {
        font-size: 12px;
        opacity: 0.9;
    }
    .info-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        margin-bottom: 25px;
        overflow: hidden;
    }
    .info-card-header {
        background: #f8f9fa;
        padding: 15px 20px;
        border-bottom: 1px solid #e9ecef;
    }
    .info-card-title {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #495057;
    }
    .info-card-body {
        padding: 20px;
    }
    .product-image-main {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e9ecef;
    }
    .main-image-wrapper {
        width: 100%;
        height: 400px;
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid #e9ecef;
        background: #f8f9fa;
    }
    .main-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .main-image-wrapper:hover img {
        transform: scale(1.05);
    }
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }
    .gallery-item {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid #e9ecef;
        transition: all 0.2s ease;
    }
    .gallery-item:hover {
        border-color: #007bff;
        transform: scale(1.05);
    }
    .gallery-image {
        width: 100%;
        height: 120px;
        object-fit: cover;
    }
    .gallery-remove {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        cursor: pointer;
    }
    .nav-tabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        color: #6c757d;
        font-weight: 500;
        padding: 12px 20px;
    }
    .nav-tabs .nav-link.active {
        border-bottom: 3px solid #007bff;
        color: #007bff;
        background: none;
    }
    .tab-content {
        padding: 20px 0;
    }
    .info-table {
        margin-bottom: 0;
    }
    .info-table td {
        padding: 12px 0;
        border: none;
        vertical-align: top;
    }
    .info-table td:first-child {
        font-weight: 600;
        color: #495057;
        width: 40%;
    }
    .stock-alert {
        margin-top: 20px;
    }
    .stock-alert .alert {
        border-radius: 8px;
        border: none;
        padding: 15px 20px;
    }
    .related-products-table {
        margin-bottom: 0;
    }
    .related-products-table th {
        background: #f8f9fa;
        border: none;
        padding: 12px;
        font-weight: 600;
        color: #495057;
    }
    .related-products-table td {
        padding: 12px;
        border: none;
        vertical-align: middle;
    }
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }
    .btn-quick-action {
        padding: 15px 20px;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.2s ease;
    }
    .btn-quick-action:hover {
        transform: translateY(-2px);
        text-decoration: none;
    }
    .no-gallery {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
        background: #f8f9fa;
        border-radius: 8px;
    }
    .no-gallery i {
        font-size: 3rem;
        margin-bottom: 15px;
        opacity: 0.3;
    }
</style>

<div class="section-content-right">
    <!-- Product Header -->
    <div class="product-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h2 class="mb-2">{{ $product->name }}</h2>
                <p class="mb-0 opacity-75">{{ $product->short_description }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-light">
                    <i class="icon-edit me-2"></i>Edit Product
                </a>
                <a href="{{ route('admin.products') }}" class="btn btn-outline-light">
                    <i class="icon-arrow-left me-2"></i>Back to Products
                </a>
            </div>
        </div>
        
        <div class="product-stats">
            <div class="stat-item">
                <div class="stat-number">{{ $product->quantity }}</div>
                <div class="stat-label">Stock Quantity</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">${{ number_format($product->regular_price, 2) }}</div>
                <div class="stat-label">Regular Price</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $product->featured ? 'Yes' : 'No' }}</div>
                <div class="stat-label">Featured</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ ucfirst($product->stock_status) }}</div>
                <div class="stat-label">Stock Status</div>
            </div>
        </div>
    </div>

    <!-- Product Images Section -->
    <div class="info-card">
        <div class="info-card-header">
            <h5 class="info-card-title">Product Images</h5>
        </div>
        <div class="info-card-body">
            <div class="row">
                <!-- Main Image -->
                <div class="col-md-6 mb-4">
                    <h6 class="mb-3">Main Product Image</h6>
                    @if($product->image)
                        <div class="main-image-wrapper">
                            <img src="{{ asset('uploads/products/'.$product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="product-image-main">
                        </div>
                    @else
                        <div class="no-gallery">
                            <i class="icon-image"></i>
                            <p>No main image available</p>
                        </div>
                    @endif
                </div>

                <!-- Gallery Images -->
                <div class="col-md-6 mb-4">
                    <h6 class="mb-3">Gallery Images</h6>
                    @if($product->images && count($product->gallery) > 0)
                        <div class="gallery-grid">
                            @foreach($product->gallery as $galleryImage)
                            <div class="gallery-item">
                                <img src="{{ asset('uploads/products/thumbnails/'.$galleryImage) }}" 
                                     alt="{{ $product->name }}" 
                                     class="gallery-image">
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="no-gallery">
                            <i class="icon-images"></i>
                            <p>No gallery images available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Product Information Tabs -->
    <div class="info-card">
        <div class="info-card-header">
            <h5 class="info-card-title">Product Information</h5>
        </div>
        <div class="info-card-body">
            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">
                        <i class="icon-info me-2"></i>Basic Details
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pricing-tab" data-bs-toggle="tab" data-bs-target="#pricing" type="button" role="tab">
                        <i class="icon-dollar-sign me-2"></i>Pricing & Stock
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button" role="tab">
                        <i class="icon-search me-2"></i>SEO & Meta
                    </button>
                </li>
            </ul>
            
            <div class="tab-content" id="productTabsContent">
                <!-- Basic Details Tab -->
                <div class="tab-pane fade show active" id="details" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table info-table">
                                <tbody>
                                    <tr>
                                        <td>Product Name:</td>
                                        <td><strong>{{ $product->name }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Slug:</td>
                                        <td><code class="bg-light px-2 py-1 rounded">{{ $product->slug }}</code></td>
                                    </tr>
                                    <tr>
                                        <td>Category:</td>
                                        <td><span class="badge bg-info">{{ $product->category->name ?? 'N/A' }}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Brand:</td>
                                        <td><span class="badge bg-secondary">{{ $product->brand->name ?? 'N/A' }}</span></td>
                                    </tr>
                                    <tr>
                                        <td>SKU:</td>
                                        <td><code class="bg-light px-2 py-1 rounded">{{ $product->SKU }}</code></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Description</h6>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-0">{{ $product->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing & Stock Tab -->
                <div class="tab-pane fade" id="pricing" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table info-table">
                                <tbody>
                                    <tr>
                                        <td>Regular Price:</td>
                                        <td><span class="text-primary fw-bold">${{ number_format($product->regular_price, 2) }}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Sale Price:</td>
                                        <td>
                                            @if($product->sale_price && $product->sale_price < $product->regular_price)
                                                <span class="text-success fw-bold">${{ number_format($product->sale_price, 2) }}</span>
                                                <span class="badge bg-success ms-2">
                                                    {{ round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100) }}% OFF
                                                </span>
                                            @else
                                                <span class="text-muted">Not set</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Stock Status:</td>
                                        <td>
                                            <span class="badge bg-{{ $product->stock_status === 'in_stock' ? 'success' : 'danger' }}">
                                                {{ ucfirst($product->stock_status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Quantity:</td>
                                        <td><strong>{{ $product->quantity }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Featured:</td>
                                        <td>
                                            <span class="badge bg-{{ $product->featured ? 'warning' : 'secondary' }}">
                                                {{ $product->featured ? 'Yes' : 'No' }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="stock-alert">
                                @if($product->stock_status === 'out_of_stock')
                                    <div class="alert alert-danger">
                                        <i class="icon-alert-triangle me-2"></i>
                                        <strong>Out of Stock!</strong> This product is currently unavailable.
                                    </div>
                                @elseif($product->quantity <= 5 && $product->quantity > 0)
                                    <div class="alert alert-warning">
                                        <i class="icon-alert-circle me-2"></i>
                                        <strong>Low Stock!</strong> Only {{ $product->quantity }} items remaining.
                                    </div>
                                @else
                                    <div class="alert alert-success">
                                        <i class="icon-check-circle me-2"></i>
                                        <strong>In Stock!</strong> {{ $product->quantity }} items available.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO & Meta Tab -->
                <div class="tab-pane fade" id="seo" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table info-table">
                                <tbody>
                                    <tr>
                                        <td>Created:</td>
                                        <td>{{ $product->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Last Updated:</td>
                                        <td>{{ $product->updated_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Product ID:</td>
                                        <td><code class="bg-light px-2 py-1 rounded">#{{ $product->id }}</code></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">SEO Information</h6>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-2"><strong>Product slug:</strong> <code>{{ $product->slug }}</code></p>
                                <p class="mb-2"><strong>URL:</strong> <code>/product/{{ $product->slug }}</code></p>
                                <p class="mb-0"><strong>Meta description:</strong> {{ Str::limit($product->short_description, 100) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="info-card">
        <div class="info-card-header">
            <h5 class="info-card-title">Related Products (Same Category)</h5>
        </div>
        <div class="info-card-body">
            <div class="table-responsive">
                <table class="table related-products-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($relatedProducts as $relatedProduct)
                        <tr>
                            <td>
                                @if($relatedProduct->image)
                                    <img src="{{ asset('uploads/products/thumbnails/'.$relatedProduct->image) }}" 
                                         alt="{{ $relatedProduct->name }}" 
                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                         style="width: 50px; height: 50px;">
                                        <i class="icon-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $relatedProduct->name }}</strong>
                                <br><small class="text-muted">{{ $relatedProduct->category->name }}</small>
                            </td>
                            <td>{{ $relatedProduct->brand->name }}</td>
                            <td>
                                @if($relatedProduct->sale_price && $relatedProduct->sale_price < $relatedProduct->regular_price)
                                    <span class="text-danger">${{ number_format($relatedProduct->sale_price, 2) }}</span>
                                    <br><small class="text-muted text-decoration-line-through">${{ number_format($relatedProduct->regular_price, 2) }}</small>
                                @else
                                    <span class="text-primary">${{ number_format($relatedProduct->regular_price, 2) }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $relatedProduct->stock_status === 'in_stock' ? 'success' : 'danger' }}">
                                    {{ ucfirst($relatedProduct->stock_status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.product.details', $relatedProduct->id) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="icon-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.product.edit', $relatedProduct->id) }}" 
                                       class="btn btn-sm btn-outline-warning">
                                        <i class="icon-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="info-card">
        <div class="info-card-header">
            <h5 class="info-card-title">Quick Actions</h5>
        </div>
        <div class="info-card-body">
            <div class="quick-actions">
                <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-primary btn-quick-action">
                    <i class="icon-edit"></i>Edit Product
                </a>
                <button class="btn btn-warning btn-quick-action" onclick="duplicateProduct()">
                    <i class="icon-copy"></i>Duplicate Product
                </button>
                <a href="{{ route('user.product.details', $product->id) }}" target="_blank" class="btn btn-info btn-quick-action">
                    <i class="icon-external-link"></i>View in Shop
                </a>
                <button class="btn btn-danger btn-quick-action" onclick="deleteProduct()">
                    <i class="icon-trash"></i>Delete Product
                </button>
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
    function duplicateProduct() {
        Swal.fire({
            title: 'Duplicate Product?',
            text: 'This will create a copy of the current product.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Duplicate',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Implement duplication logic
                Swal.fire('Success!', 'Product duplicated successfully.', 'success');
            }
        });
    }

    function deleteProduct() {
        // Set modal content
        document.getElementById('productNameToDelete').textContent = '{{ $product->name }}';
        document.getElementById('deleteForm').action = '{{ route("admin.product.delete", $product->id) }}';
        
        // Show modal
        $('#deleteModal').modal('show');
    }
</script>
@endpush
@endsection
