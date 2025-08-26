<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'type' => 'fixed',
                'value' => 10.00,
                'cart_value' => 50.00,
                'expiry_date' => Carbon::now()->addMonths(3),
                'is_active' => true,
                'max_uses' => 100,
            ],
            [
                'code' => 'SAVE20',
                'type' => 'percent',
                'value' => 20.00,
                'cart_value' => 100.00,
                'expiry_date' => Carbon::now()->addMonths(2),
                'is_active' => true,
                'max_uses' => 50,
            ],
            [
                'code' => 'FREESHIP',
                'type' => 'fixed',
                'value' => 15.00,
                'cart_value' => 75.00,
                'expiry_date' => Carbon::now()->addMonth(),
                'is_active' => true,
                'max_uses' => 200,
            ],
            [
                'code' => 'HALFOFF',
                'type' => 'percent',
                'value' => 50.00,
                'cart_value' => 200.00,
                'expiry_date' => Carbon::now()->addWeeks(2),
                'is_active' => true,
                'max_uses' => 25,
            ],
        ];

        foreach ($coupons as $couponData) {
            Coupon::create($couponData);
        }
    }
}
