@extends('layouts.admin')
@section('content')
<style>
    .table-striped th:nth-child(1), .table-striped td:nth-child(1) {
        width: 80px;   
    }
    .table-striped th:nth-child(2), .table-striped td:nth-child(2) {
        width: 280px;   
    }
    .table-striped th:nth-child(11), .table-striped td:nth-child(11) {
        width: 150px;   
    }
    .product-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e9ecef;
    }
    .product-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .product-details {
        flex: 1;
    }
    .product-name {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
        text-decoration: none;
    }
    .product-name:hover {
        color: #007bff;
    }
    .product-slug {
        font-size: 12px;
        color: #6c757d;
        background: #f8f9fa;
        padding: 2px 8px;
        border-radius: 4px;
        display: inline-block;
    }
    .status-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 500;
        text-transform: uppercase;
    }
    .status-instock { background: #d4edda; color: #155724; }
    .status-outofstock { background: #f8d7da; color: #721c24; }
    .status-featured { background: #fff3cd; color: #856404; }
    .action-buttons {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }
    .btn-view { background: #17a2b8; color: white; }
    .btn-view:hover { background: #138496; color: white; }
    .btn-edit { background: #ffc107; color: #212529; }
    .btn-edit:hover { background: #e0a800; color: #212529; }
    .btn-delete { background: #dc3545; color: white; }
    .btn-delete:hover { background: #c82333; color: white; }
    .search-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 25px;
        border: 1px solid #e9ecef;
    }
    .stats-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        text-align: center;
        margin-bottom: 20px;
    }
    .stats-number {
        font-size: 2rem;
        font-weight: 700;
        color: #007bff;
        margin-bottom: 5px;
    }
    .stats-label {
        color: #6c757d;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>

<div class="main-content-inner">
    <div class="main-content-wrap">
        <!-- Header Section -->
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <div>
                <h3 class="mb-2">Product Management</h3>
                <p class="text-muted mb-0">Manage your product catalog efficiently</p>
            </div>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{route('admin.index')}}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Products</div>
                </li>
            </ul>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number">{{ $products->total() }}</div>
                    <div class="stats-label">Total Products</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number">{{ $products->where('featured', 1)->count() }}</div>
                    <div class="stats-label">Featured</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number">{{ $products->where('stock_status', 'in_stock')->count() }}</div>
                    <div class="stats-label">In Stock</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number">{{ $products->where('stock_status', 'out_of_stock')->count() }}</div>
                    <div class="stats-label">Out of Stock</div>
                </div>
            </div>
        </div>

        <!-- Search and Actions Section -->
        <div class="search-section">
            <form method="GET" action="{{ route('admin.products') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-3">
                        <div class="filter-group">
                            <label class="filter-label">Search Products</label>
                            <div class="input-group">
                                <input type="text" placeholder="Search by name or SKU..." 
                                       class="form-control" name="search" value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="icon-search"></i> Search
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="filter-group">
                            <label class="filter-label">Stock Status</label>
                            <select class="form-select" name="stock_status">
                                <option value="">All</option>
                                <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="filter-group">
                            <label class="filter-label">Featured</label>
                            <select class="form-select" name="featured">
                                <option value="">All</option>
                                <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>Featured</option>
                                <option value="0" {{ request('featured') == '0' ? 'selected' : '' }}>Not Featured</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="filter-group">
                            <label class="filter-label">Actions</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="icon-filter me-2"></i>Apply Filters
                                </button>
                                <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary">
                                    <i class="icon-refresh me-2"></i>Clear
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex justify-content-end">
                            <a class="tf-button style-1 w208" href="{{route('admin.product.add')}}">
                                <i class="icon-plus"></i>Add New Product
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Products Table -->
        <div class="wg-box">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="icon-check-circle me-2"></i>{{session('status')}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>  
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="icon-alert-triangle me-2"></i>{{session('error')}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>  
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Sale Price</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Status</th>
                            <th>Stock</th>
                            <th>Qty</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                        <tr>
                            <td><strong>{{$product->id}}</strong></td>
                            <td>
                                <div class="product-info">
                                    <img src="{{asset('uploads/products/thumbnails')}}/{{$product->image}}" 
                                         alt="{{$product->name}}" class="product-image">
                                    <div class="product-details">
                                        <a href="{{ route('admin.product.details', $product->id) }}" 
                                           class="product-name">{{$product->name}}</a>
                                        <div class="product-slug">{{$product->slug}}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold text-primary">${{number_format($product->regular_price, 2)}}</span>
                            </td>
                            <td>
                                @if($product->sale_price && $product->sale_price < $product->regular_price)
                                    <span class="text-success fw-bold">${{number_format($product->sale_price, 2)}}</span>
                                    <div class="badge bg-success">{{round(($product->regular_price - $product->sale_price)*100/$product->regular_price)}}% OFF</div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td><code>{{$product->SKU}}</code></td>
                            <td>
                                <span class="badge bg-info">{{$product->category->name ?? 'N/A'}}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{$product->brand->name ?? 'N/A'}}</span>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    @if($product->featured == 1)
                                        <span class="status-badge status-featured">Featured</span>
                                    @endif
                                    <span class="status-badge status-{{$product->stock_status == 'in_stock' ? 'instock' : 'outofstock'}}">
                                        {{$product->stock_status == 'in_stock' ? 'In Stock' : 'Out of Stock'}}
                                    </span>
                                </div>
                            </td>
                            <td>
                                @if($product->quantity <= 5 && $product->quantity > 0)
                                    <span class="badge bg-warning">Low ({{$product->quantity}})</span>
                                @elseif($product->quantity == 0)
                                    <span class="badge bg-danger">Empty</span>
                                @else
                                    <span class="badge bg-success">{{$product->quantity}}</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold">{{$product->quantity}}</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.product.details', $product->id) }}" 
                                       class="btn-action btn-view" title="View Details">
                                        <i class="icon-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.product.edit', $product->id) }}" 
                                       class="btn-action btn-edit" title="Edit Product">
                                        <i class="icon-edit-3"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn-action btn-delete delete-product" 
                                            title="Delete Product"
                                            data-product-id="{{ $product->id }}"
                                            data-product-name="{{ $product->name }}">
                                        <i class="icon-trash-2"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{$products->withQueryString()->links('pagination::bootstrap-5')}}
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
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
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

        // Search functionality enhancement
        $('.form-search input[name="name"]').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('.table tbody tr').each(function() {
                const productName = $(this).find('.product-name').text().toLowerCase();
                const productSku = $(this).find('td:nth-child(5)').text().toLowerCase();
                
                if (productName.includes(searchTerm) || productSku.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Clear search on form submit if empty
        $('.form-search').on('submit', function(e) {
            const searchInput = $(this).find('input[name="name"]');
            if (searchInput.val().trim() === '') {
                e.preventDefault();
                $('.table tbody tr').show();
            }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    });
</script>
@endpush