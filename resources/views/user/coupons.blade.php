@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Available Coupons</h4>
                </div>
                <div class="card-body">
                    @if($availableCoupons->count() > 0)
                        <div class="row">
                            @foreach($availableCoupons as $coupon)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 border-primary">
                                    <div class="card-header bg-primary text-white text-center">
                                        <h5 class="mb-0">{{ $coupon->code }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center mb-3">
                                            @if($coupon->type === 'percent')
                                                <span class="display-6 text-success">{{ $coupon->value }}%</span>
                                                <small class="text-muted d-block">OFF</small>
                                            @else
                                                <span class="display-6 text-success">${{ number_format($coupon->value, 2) }}</span>
                                                <small class="text-muted d-block">OFF</small>
                                            @endif
                                        </div>
                                        
                                        <div class="coupon-details">
                                            <p class="mb-2">
                                                <strong>Minimum Cart Value:</strong> ${{ number_format($coupon->cart_value, 2) }}
                                            </p>
                                            <p class="mb-2">
                                                <strong>Expires:</strong> {{ $coupon->expiry_date->format('M d, Y') }}
                                            </p>
                                            @if($coupon->max_uses)
                                                <p class="mb-2">
                                                    <strong>Uses Left:</strong> {{ $coupon->max_uses - $coupon->used_count }}
                                                </p>
                                            @endif
                                        </div>
                                        
                                        <div class="text-center mt-3">
                                            <button class="btn btn-outline-primary btn-sm copy-coupon" 
                                                    data-coupon="{{ $coupon->code }}">
                                                Copy Code
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                            <h5>No Coupons Available</h5>
                            <p class="text-muted">Check back later for new offers!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($usedCoupons->count() > 0)
    <div class="row justify-content-center mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Coupon Usage History</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Coupon Code</th>
                                    <th>Discount</th>
                                    <th>Order Total</th>
                                    <th>Used On</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usedCoupons as $usage)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">{{ $usage->coupon->code }}</span>
                                    </td>
                                    <td class="text-success">
                                        -${{ number_format($usage->discount_amount, 2) }}
                                    </td>
                                    <td>${{ number_format($usage->order_total, 2) }}</td>
                                    <td>{{ $usage->used_at->format('M d, Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Coupon Application Modal -->
<div class="modal fade" id="couponModal" tabindex="-1" aria-labelledby="couponModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="couponModalLabel">Apply Coupon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="couponForm">
                    <div class="mb-3">
                        <label for="couponCode" class="form-label">Coupon Code</label>
                        <input type="text" class="form-control" id="couponCode" name="coupon_code" 
                               placeholder="Enter coupon code" required>
                    </div>
                    <div class="mb-3">
                        <label for="cartTotal" class="form-label">Cart Total</label>
                        <input type="number" class="form-control" id="cartTotal" name="cart_total" 
                               step="0.01" min="0" placeholder="0.00" required>
                    </div>
                </form>
                <div id="couponResult" class="mt-3" style="display: none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="applyCoupon">Apply Coupon</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Copy coupon code to clipboard
    $('.copy-coupon').click(function() {
        const couponCode = $(this).data('coupon');
        navigator.clipboard.writeText(couponCode).then(function() {
            // Show success message
            const button = $(this);
            const originalText = button.text();
            button.text('Copied!').addClass('btn-success').removeClass('btn-outline-primary');
            
            setTimeout(function() {
                button.text(originalText).removeClass('btn-success').addClass('btn-outline-primary');
            }, 2000);
        });
    });

    // Apply coupon
    $('#applyCoupon').click(function() {
        const formData = $('#couponForm').serialize();
        
        $.ajax({
            url: '{{ route("coupons.apply") }}',
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#couponResult').html(`
                        <div class="alert alert-success">
                            <h6>Coupon Applied Successfully!</h6>
                            <p>Discount: -$${response.coupon.discount_amount}</p>
                            <p>Final Total: $${response.coupon.final_total}</p>
                        </div>
                    `).show();
                } else {
                    $('#couponResult').html(`
                        <div class="alert alert-danger">
                            ${response.message}
                        </div>
                    `).show();
                }
            },
            error: function() {
                $('#couponResult').html(`
                    <div class="alert alert-danger">
                        An error occurred. Please try again.
                    </div>
                `).show();
            }
        });
    });
});
</script>
@endpush
