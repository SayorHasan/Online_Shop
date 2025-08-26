<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        // Get cart items from session
        $cartItems = Session::get('cart', []);
        
        if (empty($cartItems)) {
            return redirect()->route('user.cart')->with('error', 'Your cart is empty');
        }
        
        $products = [];
        $total = 0;
        $subtotal = 0;
        $tax = 0;

        foreach ($cartItems as $productId => $item) {
            $product = \App\Models\Product::with(['category', 'brand'])->find($productId);
            if ($product) {
                $item['product'] = $product;
                $item['subtotal'] = $item['price'] * $item['quantity'];
                $products[] = $item;
                $subtotal += $item['subtotal'];
            }
        }

        $tax = $subtotal * 0.1; // 10% tax
        $total = $subtotal + $tax;

        // Get user's default address
        $address = $user->addresses()->where('is_default', true)->first();
        
        // If no default address, get the first available address
        if (!$address) {
            $address = $user->addresses()->first();
        }

        return view('user.checkout', compact('products', 'subtotal', 'tax', 'total', 'address'));
    }

    public function placeOrder(Request $request)
    {
        \Log::info('Checkout placeOrder method called', [
            'request_data' => $request->all(),
            'user_id' => Auth::id()
        ]);

        $request->validate([
            'payment_mode' => 'required|in:card,paypal,cod',
            'name' => 'required_if:address,null|string|max:255',
            'phone' => 'required_if:address,null|string|max:20',
            'address' => 'required_if:address,null|string|max:500',
            'locality' => 'required_if:address,null|string|max:255',
            'city' => 'required_if:address,null|string|max:100',
            'state' => 'required_if:address,null|string|max:100',
            'zip' => 'required_if:address,null|string|max:20',
            'landmark' => 'required_if:address,null|string|max:255',
        ]);

        \Log::info('Validation passed');

        $user = Auth::user();
        $cartItems = Session::get('cart', []);
        
        \Log::info('Cart items', ['cart_items' => $cartItems]);
        
        if (empty($cartItems)) {
            return redirect()->route('user.cart')->with('error', 'Your cart is empty');
        }

        try {
            // Get or create address
            $address = null;
            if ($request->filled('name')) {
                // Create new address from form data
                $address = $user->addresses()->create([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'locality' => $request->locality,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip,
                    'landmark' => $request->landmark,
                    'country' => 'United States', // Default country
                    'is_default' => true,
                ]);
            } else {
                // Use existing address
                $address = $user->addresses()->where('is_default', true)->first();
                if (!$address) {
                    $address = $user->addresses()->first();
                }
            }

            if (!$address) {
                return back()->withErrors(['address' => 'Please provide shipping address']);
            }

            // Calculate totals
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            
            $tax = $subtotal * 0.1; // 10% tax
            $shipping = 0; // Free shipping
            $discount = 0;
            
            // Apply coupon if exists
            if (Session::has('applied_coupon')) {
                $discount = Session::get('applied_coupon_discount', 0);
            }
            
            $total = $subtotal + $tax + $shipping - $discount;

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'status' => 'ordered',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_mode,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'discount' => $discount,
                'total' => $total,
                'shipping_address_id' => $address->id,
                'notes' => $request->notes ?? '',
            ]);

            // Create order items
            foreach ($cartItems as $productId => $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            // Clear cart and coupon session
            Session::forget('cart');
            Session::forget([
                'applied_coupon',
                'applied_coupon_code',
                'applied_coupon_discount',
                'applied_coupon_final_total',
                'applied_coupon_id'
            ]);

            return redirect()->route('checkout.confirmation', ['order_id' => $order->id])
                           ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to place order. Please try again.']);
        }
    }

    public function confirmation($orderId)
    {
        $order = Order::with(['items', 'shippingAddress'])->findOrFail($orderId);
        
        // Ensure user can only view their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.order-confirmation', compact('order'));
    }
}
