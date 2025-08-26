<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add new date fields
            $table->timestamp('delivered_date')->nullable();
            $table->timestamp('canceled_date')->nullable();
        });

        // Update existing status values to match new enum
        DB::statement("UPDATE orders SET status = 'ordered' WHERE status = 'pending'");
        DB::statement("UPDATE orders SET status = 'ordered' WHERE status = 'processing'");
        DB::statement("UPDATE orders SET status = 'ordered' WHERE status = 'shipped'");
        DB::statement("UPDATE orders SET status = 'canceled' WHERE status = 'cancelled'");

        // Now change the enum
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('ordered', 'delivered', 'canceled') NOT NULL DEFAULT 'ordered'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First change enum back
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending'");

        // Update status values back
        DB::statement("UPDATE orders SET status = 'pending' WHERE status = 'ordered'");
        DB::statement("UPDATE orders SET status = 'cancelled' WHERE status = 'canceled'");

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivered_date', 'canceled_date']);
        });
    }
};
