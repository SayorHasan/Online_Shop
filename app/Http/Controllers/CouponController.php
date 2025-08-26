<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $availableCoupons = Coupon::where('is_active', true)
            ->where('expiry_date', '>=', now()->toDateString())
            ->where(function($query) {
                $query->whereNull('max_uses')
                      ->orWhereRaw('used_count < max_uses');
            })
            ->get();

        $usedCoupons = $user->couponUsages()->with('coupon')->get();

        return view('user.coupons', compact('availableCoupons', 'usedCoupons'));
    }

    public function apply(Request $request)
    {
        \Log::info('Coupon apply request:', $request->all());
        
        $request->validate([
            'coupon_code' => 'required|string|max:50',
            'cart_total' => 'required|numeric|min:0'
        ]);

        \Log::info('Validation passed, searching for coupon: ' . strtoupper($request->coupon_code));

        $coupon = Coupon::where('code', strtoupper($request->coupon_code))
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            \Log::warning('Coupon not found: ' . strtoupper($request->coupon_code));
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code'
            ]);
        }

        \Log::info('Coupon found:', ['id' => $coupon->id, 'code' => $coupon->code, 'cart_value' => $coupon->cart_value]);

        if (!$coupon->canBeUsed($request->cart_total, Auth::id())) {
            \Log::warning('Coupon cannot be used:', ['cart_total' => $request->cart_total, 'user_id' => Auth::id()]);
            return response()->json([
                'success' => false,
                'message' => 'Coupon cannot be used. Check minimum cart value or if already used.'
            ]);
        }

        $discountAmount = $coupon->calculateDiscount($request->cart_total);
        $finalTotal = $request->cart_total - $discountAmount;

        // Store coupon information in session for cart display
        session([
            'applied_coupon' => true,
            'applied_coupon_code' => $coupon->code,
            'applied_coupon_discount' => $discountAmount,
            'applied_coupon_final_total' => $finalTotal,
            'applied_coupon_id' => $coupon->id
        ]);

        return response()->json([
            'success' => true,
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'discount_amount' => $discountAmount,
                'final_total' => $finalTotal
            ],
            'message' => 'Coupon applied successfully!'
        ]);
    }

    public function remove(Request $request)
    {
        // Clear coupon from session
        session()->forget([
            'applied_coupon',
            'applied_coupon_code',
            'applied_coupon_discount',
            'applied_coupon_final_total',
            'applied_coupon_id'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Coupon removed successfully!'
        ]);
    }

    public function validateCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50',
            'cart_total' => 'required|numeric|min:0'
        ]);

        $coupon = Coupon::where('code', strtoupper($request->coupon_code))
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid coupon code'
            ]);
        }

        if (!$coupon->canBeUsed($request->cart_total, Auth::id())) {
            return response()->json([
                'valid' => false,
                'message' => 'Coupon cannot be used. Check minimum cart value or if already used.'
            ]);
        }

        $discountAmount = $coupon->calculateDiscount($request->cart_total);

        return response()->json([
            'valid' => true,
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'discount_amount' => $discountAmount
            ]
        ]);
    }
}
