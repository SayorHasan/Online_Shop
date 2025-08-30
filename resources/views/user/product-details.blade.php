@extends('layouts.app')

@section('content')
<main>
    <!-- Product Details Header -->
    <section class="container my-5">
        <div class="d-flex justify-content-start align-items-center mb-4">
            <button onclick="goBack()" class="btn btn-outline-secondary btn-sm back-btn" style="border-radius: var(--radius-md); padding: 0.75rem 1.5rem; font-weight: 600;">
                <i class="fa fa-arrow-left me-2"></i>Back to Shop
            </button>
        </div>
    </section>

    <!-- Product Details Content -->
    <section class="container mb-5">
        <div class="row">
            <!-- Product Images -->
            <div class="col-lg-6 mb-4">
                <div class="product-gallery">
                    <!-- Main Image -->
                    <div class="main-image-container mb-3">
                        <img id="main-product-image" 
                             src="{{ asset('uploads/products/thumbnails/'.$product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid rounded shadow-sm"
                             style="width: 100%; height: 500px; object-fit: cover;">
                    </div>
                    
                    <!-- Thumbnail Gallery -->
                    @if($product->images && count($product->gallery) > 0)
                    <div class="thumbnail-gallery d-flex gap-2">
                        <div class="thumbnail-item active" onclick="changeMainImage('{{ asset('uploads/products/thumbnails/'.$product->image) }}')">
                            <img src="{{ asset('uploads/products/thumbnails/'.$product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="img-fluid rounded"
                                 style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;">
                        </div>
                        @foreach($product->gallery as $galleryImage)
                        <div class="thumbnail-item" onclick="changeMainImage('{{ asset('uploads/products/thumbnails/'.$galleryImage) }}')">
                            <img src="{{ asset('uploads/products/thumbnails/'.$galleryImage) }}" 
                                 alt="{{ $product->name }}" 
                                 class="img-fluid rounded"
                                 style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;">
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-6">
                <div class="product-info" style="background: var(--bg-primary); padding: 2rem; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); border: 1px solid var(--border-color);">
                    <h1 class="h2 mb-4" style="color: var(--text-primary); font-weight: 700;">{{ $product->name }}</h1>
                    
                    <!-- Product Meta -->
                    <div class="product-meta mb-4">
                        @if($product->stock_status === 'out_of_stock')
                            <span class="badge bg-danger text-white" style="padding: 0.75rem 1.5rem; font-size: 0.9rem; border-radius: var(--radius-md);">
                                <i class="fa fa-times-circle me-2"></i>Out of Stock
                            </span>
                        @elseif($product->quantity <= 5)
                            <span class="badge bg-warning text-dark" style="padding: 0.75rem 1.5rem; font-size: 0.9rem; border-radius: var(--radius-md);">
                                <i class="fa fa-exclamation-triangle me-2"></i>Low Stock ({{ $product->quantity }} left)
                            </span>
                        @else
                            <span class="badge bg-success text-white" style="padding: 0.75rem 1.5rem; font-size: 0.9rem; border-radius: var(--radius-md);">
                                <i class="fa fa-check-circle me-2"></i>In Stock ({{ $product->quantity }} available)
                            </span>
                        @endif
                    </div>

                    <!-- Product Rating -->
                    <div class="product-rating mb-3">
                        <div class="stars d-inline-block me-2">
                            @php $filled = (int) round($averageRating); @endphp
                            @for($i=1;$i<=5;$i++)
                                <i class="fa fa-star {{ $i <= $filled ? 'text-warning' : 'text-muted' }}"></i>
                            @endfor
                        </div>
                        <span class="text-muted">({{ $averageRating ?: '0.0' }} out of 5)</span>
                    </div>

                    <!-- Product Price -->
                    <div class="product-price mb-4">
                        @if($product->sale_price)
                            <span class="h3 text-danger me-3">${{ number_format($product->sale_price, 2) }}</span>
                            <span class="h5 text-muted text-decoration-line-through">${{ number_format($product->regular_price, 2) }}</span>
                            <span class="badge bg-danger ms-2">
                                {{ round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100) }}% OFF
                            </span>
                        @else
                            <span class="h3 text-primary">${{ number_format($product->regular_price, 2) }}</span>
                        @endif
                    </div>

                    <!-- Product Description -->
                    <div class="product-description mb-4">
                        <h5>Description</h5>
                        <p class="text-muted">{{ $product->description }}</p>
                    </div>

                    <!-- Product Options -->
                    <div class="product-options mb-4">
                        <div class="row">
                                                         <div class="col-md-6 mb-3">
                                 <label class="form-label fw-bold">Quantity</label>
                                 <div class="quantity-controls">
                                     <button class="btn" type="button" onclick="decreaseQuantity()" id="decreaseBtn" title="Decrease quantity">
                                         <i class="fa fa-minus"></i>
                                     </button>
                                     <input type="number" class="form-control" id="quantity" value="1" min="1" max="{{ $product->quantity }}" onchange="validateQuantity()" title="Enter quantity">
                                     <button class="btn" type="button" onclick="increaseQuantity()" id="increaseBtn" title="Increase quantity">
                                         <i class="fa fa-plus"></i>
                                     </button>
                                 </div>
                                 <div class="stock-indicator {{ $product->quantity > 10 ? 'available' : ($product->quantity > 0 ? 'low-stock' : 'out-of-stock') }}">
                                     <i class="fa fa-{{ $product->quantity > 10 ? 'check-circle' : ($product->quantity > 0 ? 'exclamation-triangle' : 'times-circle') }} me-1"></i>
                                     Available: {{ $product->quantity }} units
                                 </div>
                             </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">SKU</label>
                                <p class="form-control-plaintext">{{ $product->SKU }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Product Actions -->
                    <div class="product-actions mb-4">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <button class="btn btn-primary btn-lg w-100 add-to-cart-btn" onclick="addToCart()" 
                                        {{ $product->stock_status === 'out_of_stock' ? 'disabled' : '' }}>
                                    <i class="fa fa-shopping-cart me-2"></i>
                                    {{ $product->stock_status === 'in_stock' ? 'Add to Cart' : 'Out of Stock' }}
                                </button>
                            </div>
                                                         <div class="col-md-6 mb-2">
                                 <button class="btn btn-outline-danger btn-lg w-100" onclick="toggleWishlist({{ $product->id }})" id="wishlistBtn">
                                     <i class="fa fa-heart me-2" id="wishlistIcon"></i>
                                     <span id="wishlistText">Add to Wishlist</span>
                                 </button>
                             </div>
                        </div>
                    </div>

                    <!-- Product Features -->
                    <div class="product-features">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-truck text-primary me-2"></i>
                                    <span class="small">Free Shipping</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-undo text-primary me-2"></i>
                                    <span class="small">30 Day Return</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-shield text-primary me-2"></i>
                                    <span class="small">Secure Payment</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-headset text-primary me-2"></i>
                                    <span class="small">24/7 Support</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Tabs -->
    <section class="container mb-5">
        <div class="product-tabs">
            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">
                        Description
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button" role="tab">
                        Specifications
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
                        Reviews
                    </button>
                </li>
            </ul>
            
            <div class="tab-content" id="productTabsContent">
                <div class="tab-pane fade show active" id="description" role="tabpanel">
                    <div class="p-4">
                        <h5>Product Description</h5>
                        <p>{{ $product->description }}</p>
                    </div>
                </div>
                <div class="tab-pane fade" id="specifications" role="tabpanel">
                    <div class="p-4">
                        <h5>Product Specifications</h5>
                        <table class="table">
                            <tbody>

                                <tr>
                                    <td><strong>SKU:</strong></td>
                                    <td>{{ $product->SKU }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Stock Status:</strong></td>
                                    <td>{{ ucfirst($product->stock_status) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Quantity Available:</strong></td>
                                    <td>{{ $product->quantity }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <div class="p-4">
                        <h5 class="mb-3">Customer Reviews</h5>
                        @forelse($reviews as $rev)
                        <div class="mb-3 border-bottom pb-2">
                            <div class="small">
                                @for($i=1;$i<=5;$i++)
                                  <i class="fa fa-star {{ $i <= $rev->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                                <span class="ms-2 text-muted">by {{ $rev->user->name }} • {{ $rev->created_at->diffForHumans() }}</span>
                            </div>
                            @if($rev->comment)
                            <div>{{ $rev->comment }}</div>
                            @endif
                        </div>
                        @empty
                        <p class="text-muted">No reviews yet. Be the first to review this product!</p>
                        @endforelse

                        @auth
                        <form class="mt-3" method="POST" action="{{ route('user.product.review', $product->id) }}">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label">Rating</label>
                                <select name="rating" class="form-select" style="max-width:200px">
                                    @for($i=5;$i>=1;$i--)
                                      <option value="{{ $i }}">{{ $i }} Star{{ $i>1?'s':'' }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Comment</label>
                                <textarea name="comment" rows="3" class="form-control" placeholder="Share your experience..."></textarea>
                            </div>
                            <button class="btn btn-dark" type="submit">Submit Review</button>
                        </form>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <section class="container mb-5">
        <h3 class="mb-4">Related Products</h3>
        <div class="row">
            @foreach($relatedProducts as $relatedProduct)
            <div class="col-md-3 mb-4">
                <div class="card h-100 product-card">
                    <img src="{{ asset('uploads/products/thumbnails/'.$relatedProduct->image) }}" 
                         class="card-img-top" alt="{{ $relatedProduct->name }}"
                         style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h6 class="card-title">{{ $relatedProduct->name }}</h6>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-primary fw-bold">
                                ${{ number_format($relatedProduct->sale_price ?? $relatedProduct->regular_price, 2) }}
                            </span>
                            <a href="{{ route('user.product.details', $relatedProduct->id) }}" 
                               class="btn btn-outline-primary btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</main>

@push('scripts')
<script>
         // Store the previous page URL when this page loads
     if (document.referrer && document.referrer !== window.location.href) {
         sessionStorage.setItem('lastPage', document.referrer);
     }
     
     // Initialize page when DOM is loaded
     document.addEventListener('DOMContentLoaded', function() {
         initializeWishlistState();
         updateQuantityButtons();
         
         // Set initial quantity value and update buttons
         const quantityInput = document.getElementById('quantity');
         if (quantityInput) {
             quantityInput.value = 1;
             updateQuantityButtons();
         }
         
         // Add input event listeners for better UX
         if (quantityInput) {
             quantityInput.addEventListener('input', validateQuantity);
             quantityInput.addEventListener('blur', validateQuantity);
             quantityInput.addEventListener('keydown', function(e) {
                 if (e.key === 'Enter') {
                     e.preventDefault();
                     validateQuantity();
                 }
             });
         }
         
         // Get current cart count on page load
         getCurrentCartCount();
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
         
         // Update any elements with cart in their ID
         const cartIdElements = document.querySelectorAll('[id*="cart"], [id*="Cart"]');
         cartIdElements.forEach(element => {
             if (element.tagName === 'SPAN' || element.tagName === 'DIV') {
                 element.textContent = count;
             }
         });
         
         // Store in sessionStorage for cross-page updates
         sessionStorage.setItem('cart_count', count);
         
         // Dispatch custom event for other components
         window.dispatchEvent(new CustomEvent('cartUpdated', { detail: { count: count } }));
         
         // Log for debugging
         console.log('Cart count updated to:', count);
     }
     
     // Function to show quick notifications
     function showQuickNotification(title, message, type = 'success') {
         // Create notification element
         const notification = document.createElement('div');
         notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
         notification.style.cssText = `
             top: 20px;
             right: 20px;
             z-index: 9999;
             min-width: 300px;
             box-shadow: 0 4px 12px rgba(0,0,0,0.15);
         `;
         
         notification.innerHTML = `
             <strong>${title}</strong> ${message}
             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
         `;
         
         // Add to page
         document.body.appendChild(notification);
         
         // Auto remove after 3 seconds
         setTimeout(() => {
             if (notification.parentNode) {
                 notification.remove();
             }
         }, 3000);
     }
    
    function goBack() {
        const lastPage = sessionStorage.getItem('lastPage');
        if (lastPage && lastPage !== window.location.href) {
            window.location.href = lastPage;
        } else {
            // Fallback to shop page if no previous page
            window.location.href = '{{ route("user.shop") }}';
        }
    }
    
    function changeMainImage(imageSrc) {
        document.getElementById('main-product-image').src = imageSrc;
        
        // Update active thumbnail
        document.querySelectorAll('.thumbnail-item').forEach(item => item.classList.remove('active'));
        event.target.closest('.thumbnail-item').classList.add('active');
    }

         // Check if product is in wishlist on page load
     let isInWishlist = false;
     
     // Initialize wishlist state
     function initializeWishlistState() {
         fetch('{{ route("wishlist.check") }}', {
             method: 'POST',
             headers: {
                 'Content-Type': 'application/json',
                 'X-CSRF-TOKEN': '{{ csrf_token() }}'
             },
             body: JSON.stringify({product_id: {{ $product->id }}})
         })
         .then(response => response.json())
         .then(data => {
             if (data.success) {
                 isInWishlist = data.inWishlist;
                 updateWishlistButton();
             }
         })
         .catch(error => {
             console.error('Error checking wishlist state:', error);
         });
     }
     
     // Update wishlist button appearance
     function updateWishlistButton() {
         const btn = document.getElementById('wishlistBtn');
         const icon = document.getElementById('wishlistIcon');
         const text = document.getElementById('wishlistText');
         
         if (isInWishlist) {
             btn.className = 'btn btn-danger btn-lg w-100';
             icon.className = 'fa fa-heart me-2';
             text.textContent = 'Remove from Wishlist';
         } else {
             btn.className = 'btn btn-outline-danger btn-lg w-100';
             icon.className = 'fa fa-heart me-2';
             text.textContent = 'Add to Wishlist';
         }
     }
     
     // Validate quantity input
     function validateQuantity() {
         const quantityInput = document.getElementById('quantity');
         if (!quantityInput) return;
         
         let value = parseInt(quantityInput.value);
         const maxQuantity = {{ $product->quantity }};
         
         // Handle invalid input
         if (isNaN(value) || value < 1) {
             value = 1;
         } else if (value > maxQuantity) {
             value = maxQuantity;
         }
         
         // Update input value
         quantityInput.value = value;
         
         // Update button states
         updateQuantityButtons();
     }
     
     // Update quantity buttons state
     function updateQuantityButtons() {
         const quantityInput = document.getElementById('quantity');
         const decreaseBtn = document.getElementById('decreaseBtn');
         const increaseBtn = document.getElementById('increaseBtn');
         
         if (!quantityInput || !decreaseBtn || !increaseBtn) return;
         
         const value = parseInt(quantityInput.value) || 1;
         const maxQuantity = {{ $product->quantity }};
         
         // Update button states
         decreaseBtn.disabled = (value <= 1);
         increaseBtn.disabled = (value >= maxQuantity);
         
         // Update button appearance
         decreaseBtn.style.opacity = (value <= 1) ? '0.5' : '1';
         increaseBtn.style.opacity = (value >= maxQuantity) ? '0.5' : '1';
     }
     
     function decreaseQuantity() {
         const quantityInput = document.getElementById('quantity');
         if (!quantityInput) return;
         
         let currentValue = parseInt(quantityInput.value) || 1;
         if (currentValue > 1) {
             quantityInput.value = currentValue - 1;
             updateQuantityButtons();
         }
     }
 
     function increaseQuantity() {
         const quantityInput = document.getElementById('quantity');
         if (!quantityInput) return;
         
         const maxQuantity = {{ $product->quantity }};
         let currentValue = parseInt(quantityInput.value) || 1;
         
         if (currentValue < maxQuantity) {
             quantityInput.value = currentValue + 1;
             updateQuantityButtons();
         }
     }

         function addToCart() {
         const quantityInput = document.getElementById('quantity');
         const quantity = parseInt(quantityInput.value);
         const productId = {{ $product->id }};
         const productName = '{{ $product->name }}';
         const maxQuantity = {{ $product->quantity }};
         
         // Validate quantity
         if (isNaN(quantity) || quantity < 1) {
             Swal.fire({
                 title: 'Invalid Quantity',
                 text: 'Please enter a valid quantity.',
                 icon: 'error',
                 confirmButtonText: 'OK'
             });
             quantityInput.value = 1;
             updateQuantityButtons();
             return;
         }
         
         if (quantity > maxQuantity) {
             Swal.fire({
                 title: 'Quantity Exceeds Stock',
                 text: `Only ${maxQuantity} units available.`,
                 icon: 'warning',
                 confirmButtonText: 'OK'
             });
             quantityInput.value = maxQuantity;
             updateQuantityButtons();
             return;
         }
         
         // Check if product is in stock
         if ({{ $product->stock_status === 'out_of_stock' ? 'true' : 'false' }}) {
             Swal.fire({
                 title: 'Out of Stock',
                 text: 'This product is currently out of stock.',
                 icon: 'warning',
                 confirmButtonText: 'OK'
             });
             return;
         }
         
         // Disable button to prevent double-click
         const btn = document.querySelector('.add-to-cart-btn');
         const originalText = btn.innerHTML;
         btn.disabled = true;
         btn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Adding...';
         
         // AJAX call to add to cart
         console.log('Sending request to add to cart:', { productId, quantity });
         fetch('{{ route("cart.add") }}', {
             method: 'POST',
             headers: {
                 'Content-Type': 'application/json',
                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
             },
             body: JSON.stringify({
                 product_id: productId,
                 quantity: quantity
             })
         })
         .then(response => {
             console.log('Response received:', response);
             return response.json();
         })
         .then(data => {
             console.log('Data received:', data);
             if (data.success) {
                  // Update cart count instantly - use total_quantity if available
                  const cartCount = data.total_quantity || data.cart_count || 0;
                  updateCartCount(cartCount);
                 
                 // Show success message - Button changes to "Added!"
                 btn.classList.remove('btn-primary');
                 btn.classList.add('btn-success');
                 btn.innerHTML = '<i class="icon-check me-2"></i>Added!';
                 
                 // Reset button after 2 seconds
                 setTimeout(function() {
                     btn.classList.remove('btn-success');
                     btn.classList.add('btn-primary');
                     btn.disabled = false;
                     btn.innerHTML = '<i class="fa fa-shopping-cart me-2"></i>Add to Cart';
                 }, 2000);
                 
                 // Show success message with cart preview
                 Swal.fire({
                     title: 'Added to Cart!',
                     html: `
                         <div class="text-center">
                             <div class="mb-3">
                                 <i class="fa fa-check-circle text-success" style="font-size: 3rem;"></i>
                             </div>
                             <p class="mb-2"><strong>${quantity} x ${productName}</strong></p>
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
                 showQuickNotification('Success!', `${productName} added to cart`, 'success');
             } else {
                 // Show error popup
                 Swal.fire({
                     title: 'Error!',
                     text: data.message || 'Failed to add product to cart.',
                     icon: 'error',
                     confirmButtonText: 'OK'
                 });
                 showQuickNotification('Error!', data.message, 'error');
                 btn.disabled = false;
                 btn.innerHTML = '<i class="fa fa-shopping-cart me-2"></i>Add to Cart';
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
             showQuickNotification('Error!', 'An error occurred while adding to cart', 'error');
             btn.disabled = false;
             btn.innerHTML = '<i class="fa fa-shopping-cart me-2"></i>Add to Cart';
         })

     }

         function toggleWishlist(productId){
         // Disable button to prevent double-click
         const btn = document.getElementById('wishlistBtn');
         const originalText = btn.innerHTML;
         btn.disabled = true;
         
         fetch('{{ route('wishlist.toggle') }}',{
             method:'POST',
             headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
             body:JSON.stringify({product_id:productId})
         })
         .then(response => response.json())
         .then(data => {
             if(data.success){
                 // Toggle wishlist state
                 isInWishlist = !isInWishlist;
                 updateWishlistButton();
                 
                 // Show appropriate notification
                 if(data.added){
                     Swal.fire({
                         title: 'Added to Wishlist!',
                         text: 'Product has been added to your wishlist.',
                         icon: 'success',
                         confirmButtonText: 'OK',
                         timer: 2000,
                         timerProgressBar: true
                     });
                 } else {
                     Swal.fire({
                         title: 'Removed from Wishlist!',
                         text: 'Product has been removed from your wishlist.',
                         icon: 'info',
                         confirmButtonText: 'OK',
                         timer: 2000,
                         timerProgressBar: true
                     });
                 }
             } else {
                 // Show error message
                 Swal.fire({
                     title: 'Error!',
                     text: data.message || 'Failed to update wishlist.',
                     icon: 'error',
                     confirmButtonText: 'OK'
                 });
             }
         })
         .catch(error => {
             console.error('Error:', error);
             Swal.fire({
                 title: 'Error!',
                 text: 'An error occurred while updating wishlist. Please try again.',
                 icon: 'error',
                 confirmButtonText: 'OK'
             });
         })
         .finally(() => {
             // Re-enable button
             btn.disabled = false;
         });
     }
</script>
@endpush

<style>
  /* SweetAlert Custom Styling */
  .swal-wide {
    width: 500px !important;
  }
  
  .swal2-popup {
    border-radius: 15px !important;
  }
  
  .swal2-title {
    color: #333 !important;
    font-weight: 600 !important;
  }
  
  .swal2-html-container {
    margin: 1rem 0 !important;
  }
  
  .swal2-confirm {
    background: #28a745 !important;
    border-radius: 25px !important;
    padding: 12px 30px !important;
  }
  
  .swal2-cancel {
    background: #6c757d !important;
    border-radius: 25px !important;
    padding: 12px 30px !important;
  }
  
  .swal2-timer-progress-bar {
    background: #28a745 !important;
  }

/* Back Button Styling */
.back-btn {
    display: inline-block;
    position: relative;
    z-index: 10;
    transition: all 0.2s ease;
    border: 1px solid #6c757d;
    background: transparent;
    color: #6c757d;
    text-decoration: none;
    line-height: 1.5;
    text-align: center;
    vertical-align: middle;
    white-space: nowrap;
    touch-action: manipulation;
}

.back-btn:hover {
    background: #6c757d;
    color: white;
    border-color: #6c757d;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(108, 117, 125, 0.3);
}

.back-btn:active {
    transform: translateY(0);
    box-shadow: 0 1px 4px rgba(108, 117, 125, 0.3);
}

.back-btn:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(108, 117, 125, 0.25);
}

.thumbnail-item.active img {
    border: 2px solid #007bff;
}

.product-card {
    transition: transform 0.2s;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.nav-tabs .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
    color: #6c757d;
}

 .nav-tabs .nav-link.active {
     border-bottom: 2px solid #007bff;
     color: #007bff;
     background: none;
 }
 
 /* Professional Quantity Controls */
 .quantity-controls {
     display: flex;
     align-items: center;
     gap: 0;
     max-width: 150px;
     border-radius: 8px;
     overflow: hidden;
     box-shadow: 0 2px 4px rgba(0,0,0,0.1);
     border: 1px solid #dee2e6;
     transition: all 0.3s ease;
 }
 
 .quantity-controls:focus-within {
     box-shadow: 0 4px 12px rgba(0,0,0,0.15);
     border-color: #007bff;
 }
 
 .quantity-controls .btn {
     border: none;
     background: #f8f9fa;
     color: #495057;
     font-weight: 600;
     padding: 8px 12px;
     transition: all 0.2s ease;
     min-width: 40px;
 }
 
 .quantity-controls .btn:hover:not(:disabled) {
     background: #e9ecef;
     color: #212529;
     transform: translateY(-1px);
 }
 
 .quantity-controls .btn:disabled {
     background: #e9ecef;
     color: #adb5bd;
     cursor: not-allowed;
     opacity: 0.6;
 }
 
 .quantity-controls .form-control {
     border: none;
     text-align: center;
     font-weight: 600;
     background: white;
     border-radius: 0;
     padding: 8px 4px;
     min-width: 60px;
 }
 
 .quantity-controls .form-control:focus {
     box-shadow: none;
     background: #f8f9fa;
 }
 
 /* Stock availability indicator */
 .stock-indicator {
     font-size: 0.875rem;
     color: #6c757d;
     margin-top: 4px;
 }
 
 .stock-indicator.available {
     color: #198754;
 }
 
 .stock-indicator.low-stock {
     color: #fd7e14;
 }
 
 .stock-indicator.out-of-stock {
     color: #dc3545;
 }
 
 /* Enhanced button states */
 .btn:disabled {
     opacity: 0.6;
     cursor: not-allowed;
 }
 
 .btn:not(:disabled):hover {
     transform: translateY(-1px);
     box-shadow: 0 4px 8px rgba(0,0,0,0.15);
 }
 
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
 
 /* Responsive quantity controls */
 @media (max-width: 768px) {
     .quantity-controls {
         max-width: 120px;
     }
     
     .quantity-controls .btn {
         min-width: 35px;
         padding: 6px 8px;
     }
     
     .quantity-controls .form-control {
         min-width: 50px;
         padding: 6px 2px;
     }
 }
</style>
@endsection
