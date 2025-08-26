<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\Auth;

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

        // Only show in-stock products for customers
        $query->where('stock_status', 'in_stock');

        $products = $query->orderBy('featured', 'DESC')
                         ->orderBy('created_at', 'DESC')
                         ->paginate(12);
        
        // Get categories with product counts
        $categories = Category::withCount(['products' => function($query) {
            $query->where('stock_status', 'in_stock');
        }])->orderBy('name')->get();
        
        // Get brands with product counts
        $brands = Brand::withCount(['products' => function($query) {
            $query->where('stock_status', 'in_stock');
        }])->orderBy('name')->get();
        
        // Get featured products for sidebar
        $featuredProducts = Product::with(['category', 'brand'])
            ->where('featured', 1)
            ->where('stock_status', 'in_stock')
            ->limit(5)
            ->get();
        
        // Get highlighted product if specified
        $highlightedProduct = null;
        if ($request->filled('highlight')) {
            $highlightedProduct = Product::with(['category', 'brand'])
                ->where('stock_status', 'in_stock')
                ->find($request->highlight);
        }
        
        // Get shop statistics
        $shopStats = [
            'total_products' => Product::where('stock_status', 'in_stock')->count(),
            'total_categories' => Category::count(),
            'total_brands' => Brand::count(),
            'featured_products' => Product::where('featured', 1)->where('stock_status', 'in_stock')->count(),
            'new_arrivals' => Product::where('stock_status', 'in_stock')
                                   ->where('created_at', '>=', now()->subDays(30))
                                   ->count()
        ];
        
        return view('user.shop', compact(
            'products', 
            'categories', 
            'brands', 
            'highlightedProduct',
            'featuredProducts',
            'shopStats'
        ));
    }

    public function productDetails($id)
    {
        $product = Product::with(['category', 'brand', 'gallery'])
            ->findOrFail($id);
        
        // Get related products from same category
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['category', 'brand'])
            ->take(4)
            ->get();
        
        return view('user.product-details', compact('product', 'relatedProducts'));
    }

    public function publicProductDetails($id)
    {
        try {
            $product = Product::with(['category', 'brand', 'gallery'])
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
}
