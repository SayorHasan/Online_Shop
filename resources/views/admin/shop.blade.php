@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Shop</h3>
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
                    <div class="text-tiny">Shop</div>
                </li>
            </ul>
        </div>

        <!-- Shop Controls -->
        <div class="wg-box mb-30">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search">
                        <fieldset class="name">
                            <input type="text" placeholder="Search products..." class="" name="search"
                                tabindex="2" value="{{ request('search') }}" aria-required="true">
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-sm text-gray-600">
                        Total Products: <span class="font-semibold">{{ $products->total() }}</span>
                    </div>
                    <a class="tf-button style-1 w208" href="{{route('admin.product.add')}}"><i
                            class="icon-plus"></i>Add Product</a>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="wg-box">
            <div class="products-grid row row-cols-2 row-cols-md-3 row-cols-lg-4" id="products-grid">
                @foreach ($products as $product)
                <div class="product-card-wrapper col mb-4">
                    <div class="product-card h-100">
                        <div class="pc__img-wrapper position-relative">
                            <!-- Main Product Image Only -->
                            <div class="product-image-container">
                                @if($product->image)
                                    <img loading="lazy" src="{{asset('uploads/products/thumbnails/'.$product->image)}}" 
                                         width="330" height="400" alt="{{$product->name}}" class="pc__img w-100 h-100 object-fit-cover">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="width: 330px; height: 400px;">
                                        <i class="icon-image text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
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

                            <!-- Admin Actions -->
                            <div class="position-absolute top-0 end-0 p-2">
                                <a href="{{ route('admin.product.edit', $product->id) }}" 
                                   class="btn btn-sm btn-outline-primary me-1" title="Edit Product">
                                    <i class="icon-edit-3"></i>
                                </a>
                                <form action="{{ route('admin.product.delete', $product->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger delete-product" 
                                            title="Delete Product" data-product-name="{{ $product->name }}">
                                        <i class="icon-trash-2"></i>
                                    </button>
                                </form>
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
                            
                            <div class="product-card__review d-flex align-items-center mb-2">
                                <div class="reviews-group d-flex me-2">
                                    <i class="icon-star text-warning"></i>
                                    <i class="icon-star text-warning"></i>
                                    <i class="icon-star text-warning"></i>
                                    <i class="icon-star text-warning"></i>
                                    <i class="icon-star text-warning"></i>
                                </div>
                                <span class="reviews-note text-muted">5.0 ({{ rand(10, 100) }}+ reviews)</span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">SKU: {{$product->SKU}}</small>
                                <small class="text-muted">Qty: {{$product->quantity}}</small>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{$products->withQueryString()->links('pagination::bootstrap-5')}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function(){
        // SweetAlert delete confirmation
        $('.delete-product').on('click', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            var productName = $(this).data('product-name');
            
            swal({
                title: "Are you sure?",
                text: "You want to delete " + productName + "?",
                type: "warning",
                buttons: ["No", "Yes"],
                confirmButtonColor: '#dc3545'
            }).then(function(result) {
                if (result) {
                    form.submit();
                }
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
