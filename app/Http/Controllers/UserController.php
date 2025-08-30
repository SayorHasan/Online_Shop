<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        // Get user-specific information
        $user = Auth::user();
        
        // Get counts for user dashboard
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalBrands = Brand::count();
        
        // Get featured products for user
        $featuredProducts = Product::where('featured', 1)
            ->with(['category', 'brand'])
            ->take(6)
            ->get();
            
        // Get latest products
        $latestProducts = Product::with(['category', 'brand'])
            ->orderBy('created_at', 'DESC')
            ->take(6)
            ->get();
        
        return view('user.index', compact(
            'user', 'totalProducts', 'totalCategories', 'totalBrands',
            'featuredProducts', 'latestProducts'
        ));
    }

    public function shop(Request $request)
    {
        $query = Product::with(['category', 'brand']);

        // Apply category filter if specified
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Apply other filters if needed
        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('SKU', 'like', "%{$search}%");
            });
        }

        // Price: filter by effective price (sale_price if set, else regular_price)
        if ($request->filled('max_price')) {
            $max = (float) $request->max_price;
            $query->whereRaw('COALESCE(sale_price, regular_price) <= ?', [$max]);
        }

        // Apply stock status filter
        if ($request->filled('stock')) {
            if ($request->stock === 'in_stock') {
                $query->where('stock_status', 'in_stock');
            } elseif ($request->stock === 'out_of_stock') {
                $query->where('stock_status', 'out_of_stock');
            } elseif ($request->stock === 'low_stock') {
                $query->where('quantity', '<=', 5)->where('quantity', '>', 0);
            }
        }

        // Show all products (including out of stock) but with stock status indicators
        // This allows customers to see products even when they're temporarily out of stock

        $products = $query->orderBy('featured', 'DESC')
                         ->orderBy('stock_status', 'ASC') // Show in-stock products first
                         ->orderBy('created_at', 'DESC')
                         ->paginate(12)
                         ->appends($request->query());
        
        // Get categories with product counts (including out of stock for admin visibility)
        $categories = Category::withCount(['products'])->orderBy('name')->get();
        
        // Get brands with product counts (including out of stock for admin visibility)
        $brands = Brand::withCount(['products'])->orderBy('name')->get();
        
        // Get featured products for sidebar (show all, including out of stock)
        $featuredProducts = Product::with(['category', 'brand'])
            ->where('featured', 1)
            ->limit(5)
            ->get();
        
        // Get highlighted product if specified
        $highlightedProduct = null;
        if ($request->filled('highlight')) {
            $highlightedProduct = Product::with(['category', 'brand'])
                ->find($request->highlight);
        }
        
        // Get shop statistics
        $shopStats = [
            'total_products' => Product::count(),
            'in_stock_products' => Product::where('stock_status', 'in_stock')->count(),
            'out_of_stock_products' => Product::where('stock_status', 'out_of_stock')->count(),
            'total_categories' => Category::count(),
            'total_brands' => Brand::count(),
            'featured_products' => Product::where('featured', 1)->count(),
            'new_arrivals' => Product::where('created_at', '>=', now()->subDays(30))->count()
        ];
        
        $wishlistProductIds = auth()->check() ? Auth::user()->wishlist()->pluck('products.id')->toArray() : [];

        return view('user.shop', compact(
            'products', 
            'categories', 
            'brands', 
            'highlightedProduct',
            'featuredProducts',
            'shopStats',
            'wishlistProductIds'
        ));
    }

    public function productDetails($id)
    {
        $product = Product::with(['category', 'brand', 'reviews.user'])
            ->findOrFail($id);
        
        // Get related products from same category
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['category', 'brand'])
            ->take(4)
            ->get();
        
        $averageRating = round($product->reviews()->where('approved',true)->avg('rating'),1);
        $reviews = $product->reviews()->where('approved',true)->latest()->take(10)->get();
        return view('user.product-details', compact('product', 'relatedProducts','averageRating','reviews'));
    }

    public function publicProductDetails($id)
    {
        try {
            $product = Product::with(['category', 'brand'])
                ->findOrFail($id);
            
            // Get related products from same category
            $relatedProducts = Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->with(['category', 'brand'])
                ->take(4)
                ->get();
            
            \Log::info('Public product details accessed', [
                'product_id' => $id,
                'product_name' => $product->name,
                'user_agent' => request()->userAgent()
            ]);
            
            return view('user.product-details', compact('product', 'relatedProducts'));
            
        } catch (\Exception $e) {
            \Log::error('Error in publicProductDetails: ' . $e->getMessage(), [
                'product_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            abort(404, 'Product not found');
        }
    }

    public function orders()
    {
        $user = Auth::user();
        $orders = \App\Models\Order::where('user_id', $user->id)
                                   ->with(['items.product.category', 'items.product.brand', 'shippingAddress'])
                                   ->orderBy('created_at', 'DESC')
                                   ->paginate(10);
        
        return view('user.orders', compact('orders'));
    }

    public function orderDetails($id)
    {
        $user = Auth::user();
        $order = \App\Models\Order::where('user_id', $user->id)
                                  ->with(['items.product.category', 'items.product.brand', 'shippingAddress'])
                                  ->findOrFail($id);
        
        return view('user.order-details', compact('order'));
    }

    public function account_cancel_order(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);

        $order = \App\Models\Order::where('id', $request->order_id)
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->firstOrFail();

        if ($order->status === 'canceled') {
            return back()->with('status', 'Order is already canceled.');
        }
        if ($order->status === 'delivered') {
            return back()->with('status', 'Delivered orders cannot be canceled.');
        }

        $order->status = 'canceled';
        $order->canceled_date = \Carbon\Carbon::now();
        $order->payment_status = 'refunded';
        $order->save();

        return back()->with('status', 'Order has been cancelled successfully!');
    }

    // Account details page
    public function accountDetails()
    {
        $user = Auth::user();
        $addresses = $user->addresses()->orderByDesc('is_default')->get();
        return view('user.account-details', compact('user', 'addresses'));
    }

    public function updateAccount(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'mobile' => 'nullable|string|max:30',
        ]);

        $user->update($validated);
        return back()->with('status', 'Account details updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('status', 'Password updated successfully.');
    }

    public function toggleWishlist(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        $user = Auth::user();
        $exists = $user->wishlist()->where('product_id', $request->product_id)->exists();
        if ($exists) {
            $user->wishlist()->detach($request->product_id);
            return response()->json(['success' => true, 'added' => false]);
        }
        $user->wishlist()->attach($request->product_id);
        return response()->json(['success' => true, 'added' => true]);
    }

    public function checkWishlist(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        
        $user = Auth::user();
        $inWishlist = $user->wishlist()->where('product_id', $request->product_id)->exists();
        
        return response()->json([
            'success' => true,
            'inWishlist' => $inWishlist
        ]);
    }

    public function wishlist()
    {
        $products = Auth::user()->wishlist()->with(['category','brand'])->paginate(12);
        return view('user.wishlist', compact('products'));
    }

    public function submitReview($id, Request $request)
    {
        $product = Product::findOrFail($id);
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ]);
        \App\Models\Review::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'approved' => true,
        ]);
        return back()->with('status','Review submitted successfully.');
    }

    public function downloadInvoice($id)
    {
        $order = \App\Models\Order::with(['items','shippingAddress'])->findOrFail($id);
        if ($order->user_id !== Auth::id()) { abort(403); }
        
        // Force delete old invoice files to ensure fresh generation
        try {
            $oldInvoicePath = storage_path('app/invoices/invoice-' . $order->order_number . '.pdf');
            $oldInvoiceHtmlPath = storage_path('app/invoices/invoice-' . $order->order_number . '.html');
            
            if (file_exists($oldInvoicePath)) {
                unlink($oldInvoicePath);
            }
            
            if (file_exists($oldInvoiceHtmlPath)) {
                unlink($oldInvoiceHtmlPath);
            }
        } catch (\Throwable $e) {
            \Log::warning('Failed to delete old invoice files: ' . $e->getMessage());
        }
        
        // Generate fresh invoice with current order status
        $invoice = app(\App\Services\InvoiceService::class)->generateForOrder($order);
        $ext = str_contains($invoice['mime'],'pdf') ? '.pdf' : '.html';
        $filename = 'invoice-'.$order->order_number.$ext;
        return response()->download($invoice['path'], $filename, ['Content-Type' => $invoice['mime']]);
    }
}
