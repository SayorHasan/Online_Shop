<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AddressController;
use App\Http\Middleware\AuthAdmin;



Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');

// Static pages
Route::view('/about', 'static.about')->name('static.about');
Route::view('/contact', 'static.contact')->name('static.contact');
Route::view('/privacy', 'static.privacy')->name('static.privacy');
Route::view('/terms', 'static.terms')->name('static.terms');

// Public product viewing route (no authentication required)
Route::get('/product/{id}/view', [UserController::class, 'publicProductDetails'])->name('public.product.details');

// (removed) debug route /test-product/{id}

Route::middleware(['auth'])->group(function(){
    Route::get('/account.dashboard', [UserController::class, 'index'])->name('user.index');
    Route::get('/shop', [UserController::class, 'shop'])->name('user.shop');
    Route::get('/product/{id}', [UserController::class, 'productDetails'])->name('user.product.details');
    Route::post('/product/{id}/review', [UserController::class, 'submitReview'])->name('user.product.review');
    // Account details
    Route::get('/account/details', [UserController::class, 'accountDetails'])->name('user.account.details');
    Route::post('/account/details', [UserController::class, 'updateAccount'])->name('user.account.update');
    Route::post('/account/password', [UserController::class, 'updatePassword'])->name('user.account.password');
    
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
    
    // Checkout routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('user.checkout');
    Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.place.order');
    Route::get('/checkout/confirmation/{order_id}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');
    
    // Address routes
    Route::get('/addresses', [AddressController::class, 'index'])->name('user.addresses');
    Route::get('/addresses/create', [AddressController::class, 'create'])->name('user.address.create');
    Route::post('/addresses', [AddressController::class, 'store'])->name('user.address.store');
    Route::get('/addresses/{id}/edit', [AddressController::class, 'edit'])->name('user.address.edit');
    Route::put('/addresses/{id}', [AddressController::class, 'update'])->name('user.address.update');
    Route::delete('/addresses/{id}', [AddressController::class, 'destroy'])->name('user.address.destroy');
    Route::patch('/addresses/{id}/set-default', [AddressController::class, 'setDefault'])->name('user.address.set-default');
    
    // Order routes
    Route::get('/orders', [UserController::class, 'orders'])->name('user.orders');
    Route::get('/order/{id}/details', [UserController::class, 'orderDetails'])->name('user.order.details');
    Route::get('/order/{id}/invoice', [UserController::class, 'downloadInvoice'])->name('user.order.invoice');
    Route::put('/account-order/cancel-order',[UserController::class,'account_cancel_order'])->name('user.account_cancel_order');
});

    // Wishlist
    Route::post('/wishlist/toggle', [UserController::class, 'toggleWishlist'])->name('wishlist.toggle');
    Route::post('/wishlist/check', [UserController::class, 'checkWishlist'])->name('wishlist.check');
    Route::get('/wishlist', [UserController::class, 'wishlist'])->name('user.wishlist');

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
    
    // Order routes
    Route::get('/admin/orders',[AdminController::class,'orders'])->name('admin.orders');
    Route::get('/admin/order/{id}/details',[AdminController::class,'order_details'])->name('admin.order.details');
    Route::put('/admin/order/status/update',[AdminController::class,'update_order_status'])->name('admin.order.status.update');
    Route::get('/admin/orders/count',[AdminController::class,'ordersCount'])->name('admin.orders.count');
    Route::get('/admin/notifications',[AdminController::class,'notifications'])->name('admin.notifications');
    Route::get('/admin/notifications/count',[AdminController::class,'notificationsCount'])->name('admin.notifications.count');
    Route::post('/admin/notifications/{id}/read',[AdminController::class,'markNotificationRead'])->name('admin.notifications.read');
    
});
