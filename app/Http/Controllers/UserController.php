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

    public function shop()
    {
        $products = Product::with(['category', 'brand'])
            ->orderBy('created_at', 'DESC')
            ->paginate(12);
        
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        
        return view('user.shop', compact('products', 'categories', 'brands'));
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
}
