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
        // First, convert the column to string to avoid enum constraints
        DB::statement("ALTER TABLE products MODIFY COLUMN stock_status VARCHAR(20)");
        
        // Update the existing values
        DB::table('products')->where('stock_status', 'instock')->update(['stock_status' => 'in_stock']);
        DB::table('products')->where('stock_status', 'outofstock')->update(['stock_status' => 'out_of_stock']);
        
        // Now convert back to enum with the new values
        DB::statement("ALTER TABLE products MODIFY COLUMN stock_status ENUM('in_stock', 'out_of_stock')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert to string first
        DB::statement("ALTER TABLE products MODIFY COLUMN stock_status VARCHAR(20)");
        
        // Revert back to old values
        DB::table('products')->where('stock_status', 'in_stock')->update(['stock_status' => 'instock']);
        DB::table('products')->where('stock_status', 'out_of_stock')->update(['stock_status' => 'outofstock']);
        
        // Convert back to enum with old values
        DB::statement("ALTER TABLE products MODIFY COLUMN stock_status ENUM('instock', 'outofstock')");
    }
};
