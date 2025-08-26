<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CouponController;
use App\Http\Middleware\AuthAdmin;



Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');

// Public product viewing route (no authentication required)
Route::get('/product/{id}/view', [UserController::class, 'publicProductDetails'])->name('public.product.details');

// Test route for debugging
Route::get('/test-product/{id}', function($id) {
    $product = \App\Models\Product::find($id);
    if ($product) {
        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'stock_status' => $product->stock_status,
                'category' => $product->category ? $product->category->name : 'N/A',
                'brand' => $product->brand ? $product->brand->name : 'N/A'
            ]
        ]);
    }
    return response()->json(['success' => false, 'message' => 'Product not found']);
});

Route::middleware(['auth'])->group(function(){
    Route::get('/account.dashboard', [UserController::class, 'index'])->name('user.index');
    Route::get('/shop', [UserController::class, 'shop'])->name('user.shop');
    Route::get('/product/{id}', [UserController::class, 'productDetails'])->name('user.product.details');
    
    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('user.cart');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::put('/cart/update', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::delete('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
    Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
    
    // Coupon routes
    Route::get('/coupons', [CouponController::class, 'index'])->name('user.coupons');
    Route::post('/coupons/apply', [CouponController::class, 'apply'])->name('coupons.apply');
    Route::post('/coupons/remove', [CouponController::class, 'remove'])->name('coupons.remove');
    Route::post('/coupons/validate', [CouponController::class, 'validateCoupon'])->name('coupons.validate');
});

Route::middleware(['auth',AuthAdmin::class])->group(function(){
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    Route::get('/admin/brands',[AdminController::class,'brands'])->name('admin.brands');
    Route::get('/admin/brand/add',[AdminController::class,'add_brand'])->name('admin.brand.add');
    Route::post('/admin/brand/store',[AdminController::class,'brand_store'])->name('admin.brand.store');
    Route::get('/admin/brand/edit/{id}',[AdminController::class,'brand_edit'])->name('admin.brand.edit');
    Route::put('/admin/brand/update',[AdminController::class,'brand_update'])->name('admin.brand.update');
    Route::delete('/admin/brand/{id}/delete',[AdminController::class,'brand_delete'])->name('admin.brand.delete');

    Route::get('/admin/categories',[AdminController::class,'categories'])->name('admin.categories');
    Route::get('/admin/category/add',[AdminController::class,'category_add'])->name('admin.category.add');
    Route::post('/admin/category/store',[AdminController::class,'category_store'])->name('admin.category.store');
    Route::get('/admin/category/{id}/edit',[AdminController::class,'category_edit'])->name('admin.category.edit');
    Route::put('/admin/category/update',[AdminController::class,'category_update'])->name('admin.category.update');
    Route::delete('/admin/category/{id}/delete',[AdminController::class,'category_delete'])->name('admin.category.delete');

    Route::get('/admin/products',[AdminController::class,'products'])->name('admin.products');
    Route::get('/admin/product/add',[AdminController::class,'product_add'])->name('admin.product.add');
    Route::post('/admin/product/store',[AdminController::class,'product_store'])->name('admin.product.store');
    Route::get('/admin/product/{id}/edit',[AdminController::class,'product_edit'])->name('admin.product.edit');
    Route::put('/admin/product/update',[AdminController::class,'product_update'])->name('admin.product.update');
    Route::delete('/admin/product/{id}/delete',[AdminController::class,'product_delete'])->name('admin.product.delete');
    Route::get('/admin/shop',[AdminController::class,'shop'])->name('admin.shop');
    Route::get('/admin/product/{id}',[AdminController::class,'productDetails'])->name('admin.product.details');

    // Coupon routes
    Route::get('/admin/coupons',[AdminController::class,'coupons'])->name('admin.coupons');
    Route::get('/admin/coupon/add',[AdminController::class,'add_coupon'])->name('admin.coupon.add');
    Route::post('/admin/coupon/store',[AdminController::class,'store_coupon'])->name('admin.coupon.store');
    Route::get('/admin/coupon/{id}/edit',[AdminController::class,'edit_coupon'])->name('admin.coupon.edit');
    Route::put('/admin/coupon/update',[AdminController::class,'update_coupon'])->name('admin.coupon.update');
    Route::delete('/admin/coupon/{id}/delete',[AdminController::class,'delete_coupon'])->name('admin.coupon.delete');
    Route::get('/admin/coupon/{id}/toggle-status',[AdminController::class,'toggle_coupon_status'])->name('admin.coupon.toggle-status');
    
});
