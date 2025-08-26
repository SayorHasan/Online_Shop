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
                                    <div class="text-center pt-3">
                                        <span class="badge bg-dark px-3 py-2" style="font-size:0.95rem; border-radius:.35rem;">Code: <strong>{{ $coupon->code }}</strong></span>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center mb-3 mt-2">
                                            @if($coupon->type === 'percent')
                                                <span class="display-6" style="color:#111; font-weight:700;">{{ $coupon->value }}%</span>
                                                <small class="d-block" style="color:#111; opacity:.8;">OFF</small>
                                            @else
                                                <span class="display-6" style="color:#111; font-weight:700;">${{ number_format($coupon->value, 2) }}</span>
                                                <small class="d-block" style="color:#111; opacity:.8;">OFF</small>
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

<!-- Simple toast for copy feedback -->
<div id="copyToast" class="toast align-items-center text-white bg-success border-0 position-fixed" role="status" aria-live="polite" aria-atomic="true" style="right:1rem; bottom:1rem; z-index:1080; display:none;">
  <div class="d-flex">
    <div class="toast-body">
      Coupon code copied to clipboard
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Copy coupon code to clipboard with clear feedback
    $('.copy-coupon').on('click', function() {
        const button = $(this); // capture BEFORE async call
        const couponCode = button.data('coupon');

        function afterCopy() {
            const originalText = button.text();
            button.text('Copied!').addClass('btn-success').removeClass('btn-outline-primary');
            // show toast-like message
            const $toast = $('#copyToast');
            $toast.fadeIn(150, function(){
                setTimeout(function(){ $toast.fadeOut(200); }, 1500);
            });
            setTimeout(function() {
                button.text(originalText).removeClass('btn-success').addClass('btn-outline-primary');
            }, 2000);
        }

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(couponCode).then(afterCopy).catch(function(){
                // Fallback
                const temp = $('<input>');
                $('body').append(temp);
                temp.val(couponCode).select();
                document.execCommand('copy');
                temp.remove();
                afterCopy();
            });
        } else {
            // Very old fallback
            const temp = $('<input>');
            $('body').append(temp);
            temp.val(couponCode).select();
            document.execCommand('copy');
            temp.remove();
            afterCopy();
        }
    });
});
</script>
@endpush
