<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['fixed', 'percent']);
            $table->decimal('value', 10, 2);
            $table->decimal('cart_value', 10, 2)->comment('Minimum cart value to use coupon');
            $table->date('expiry_date');
            $table->boolean('is_active')->default(true);
            $table->integer('max_uses')->nullable();
            $table->integer('used_count')->default(0);
            $table->timestamps();
        });

        // Create coupon usage tracking table
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('discount_amount', 10, 2);
            $table->decimal('order_total', 10, 2);
            $table->timestamp('used_at');
            $table->timestamps();
            
            $table->unique(['coupon_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_usages');
        Schema::dropIfExists('coupons');
    }
};
