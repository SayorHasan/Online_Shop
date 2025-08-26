<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'cart_value',
        'expiry_date',
        'is_active',
        'max_uses'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'is_active' => 'boolean',
        'value' => 'decimal:2',
        'cart_value' => 'decimal:2',
    ];

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_usages');
    }

    public function isValid()
    {
        $now = now();
        
        return $this->is_active &&
               $this->expiry_date >= $now->toDateString() &&
               ($this->max_uses === null || $this->used_count < $this->max_uses);
    }

    public function calculateDiscount($subtotal)
    {
        if ($this->type === 'percent') {
            return ($subtotal * $this->value) / 100;
        }
        
        return $this->value;
    }

    public function canBeUsed($subtotal, $userId = null)
    {
        if (!$this->isValid()) {
            return false;
        }

        if ($subtotal < $this->cart_value) {
            return false;
        }

        // Check if user has already used this coupon
        if ($userId && $this->usages()->where('user_id', $userId)->exists()) {
            return false;
        }

        return true;
    }

    public function useCoupon($userId, $subtotal, $discountAmount)
    {
        // Create usage record
        $this->usages()->create([
            'user_id' => $userId,
            'discount_amount' => $discountAmount,
            'order_total' => $subtotal,
            'used_at' => now(),
        ]);

        // Increment usage count
        $this->increment('used_count');
    }
}
