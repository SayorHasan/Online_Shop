<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Session::get('cart', []);
        $products = [];
        $total = 0;
        $subtotal = 0;
        $tax = 0;

        foreach ($cartItems as $productId => $item) {
            $product = Product::with(['category', 'brand'])->find($productId);
            if ($product) {
                $item['product'] = $product;
                $item['subtotal'] = $item['price'] * $item['quantity'];
                $products[] = $item;
                $subtotal += $item['subtotal'];
            }
        }

        $tax = $subtotal * 0.1; // 10% tax
        $total = $subtotal + $tax;

        return view('user.cart', compact('products', 'subtotal', 'tax', 'total'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::find($request->product_id);
        
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }

        if ($product->stock_status === 'out_of_stock') {
            return response()->json(['success' => false, 'message' => 'Product is out of stock']);
        }

        if ($product->quantity < $request->quantity) {
            return response()->json(['success' => false, 'message' => 'Not enough stock available']);
        }

        $cart = Session::get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            // Check if adding more would exceed available stock
            $newTotalQuantity = $cart[$productId]['quantity'] + $request->quantity;
            if ($newTotalQuantity > $product->quantity) {
                return response()->json([
                    'success' => false, 
                    'message' => "Cannot add more items. You already have {$cart[$productId]['quantity']} in cart, and only {$product->quantity} available in stock."
                ]);
            }
            $cart[$productId]['quantity'] = $newTotalQuantity;
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->sale_price ?: $product->regular_price,
                'quantity' => $request->quantity,
                'image' => $product->image,
                'category' => $product->category->name ?? 'N/A',
                'brand' => $product->brand->name ?? 'N/A'
            ];
        }

        Session::put('cart', $cart);

        // Calculate total quantity
        $totalQuantity = 0;
        foreach ($cart as $item) {
            $totalQuantity += $item['quantity'];
        }

        return response()->json([
            'success' => true, 
            'message' => 'Product added to cart successfully',
            'cart_count' => count($cart),
            'total_quantity' => $totalQuantity
        ]);
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Session::get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            $product = Product::find($productId);
            
            if ($product->quantity < $request->quantity) {
                return response()->json(['success' => false, 'message' => 'Not enough stock available']);
            }

            $cart[$productId]['quantity'] = $request->quantity;
            Session::put('cart', $cart);

            $subtotal = $cart[$productId]['price'] * $request->quantity;
            
            return response()->json([
                'success' => true, 
                'message' => 'Quantity updated successfully',
                'subtotal' => number_format($subtotal, 2),
                'cart_count' => count($cart)
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Product not found in cart']);
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $cart = Session::get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);

            return response()->json([
                'success' => true, 
                'message' => 'Product removed from cart successfully',
                'cart_count' => count($cart)
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Product not found in cart']);
    }

    public function clearCart()
    {
        Session::forget('cart');
        
        return response()->json([
            'success' => true, 
            'message' => 'Cart cleared successfully',
            'cart_count' => 0
        ]);
    }

    public function getCartCount()
    {
        $cart = Session::get('cart', []);
        $totalQuantity = 0;
        
        foreach ($cart as $item) {
            $totalQuantity += $item['quantity'];
        }
        
        return response()->json([
            'count' => count($cart), // Number of unique products
            'total_quantity' => $totalQuantity // Total quantity of all items
        ]);
    }
}
