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

        // Validate stock availability before processing order
        $stockErrors = [];
        foreach ($cartItems as $productId => $item) {
            $product = \App\Models\Product::find($productId);
            if (!$product) {
                $stockErrors[] = "Product not found.";
                continue;
            }
            
            if (!$product->isInStock()) {
                $stockErrors[] = "Product '{$product->name}' is out of stock.";
                continue;
            }
            
            if (!$product->hasStock($item['quantity'])) {
                $stockErrors[] = "Product '{$product->name}' has only {$product->quantity} items available, but you're trying to order {$item['quantity']}.";
            }
        }
        
        if (!empty($stockErrors)) {
            return back()->withErrors(['stock' => $stockErrors]);
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

            // Create order items and reduce stock
            foreach ($cartItems as $productId => $item) {
                $product = \App\Models\Product::find($productId);
                
                // Double-check stock availability before reducing
                if ($product && $product->hasStock($item['quantity'])) {
                    // Reduce stock
                    $product->reduceStock($item['quantity']);
                    
                    // Create order item
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $productId,
                        'product_name' => $item['name'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);
                    
                    \Log::info("Stock reduced for product {$product->name}", [
                        'product_id' => $productId,
                        'quantity_reduced' => $item['quantity'],
                        'remaining_stock' => $product->quantity
                    ]);
                } else {
                    // This shouldn't happen due to validation above, but handle gracefully
                    \Log::error("Stock validation failed for product {$productId} during order processing");
                    throw new \Exception("Stock validation failed for one or more products");
                }
            }

            // Notify admins via database notification
            try {
                $admins = \App\Models\User::where('utype','ADM')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new \App\Notifications\NewOrderPlaced($order));
                }
            } catch (\Throwable $e) { \Log::warning('Admin notify failed: '.$e->getMessage()); }

            // Auto-remove purchased items from wishlist for this user
            try {
                $user->wishlist()->detach(array_keys($cartItems));
            } catch (\Throwable $e) {
                \Log::warning('Wishlist detach after order failed', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            }

            // Generate invoice and send email
            try {
                $order->load(['items','shippingAddress','user']);
                $invoice = app(\App\Services\InvoiceService::class)->generateForOrder($order);
                \Mail::send('emails.invoice', ['order' => $order], function($message) use ($order, $invoice) {
                    $message->to($order->user->email, $order->user->name)
                            ->subject('Order Confirmation '.$order->order_number);
                    $message->attach($invoice['path'], ['as' => 'invoice-'.$order->order_number.(str_contains($invoice['mime'],'pdf')?'.pdf':'.html'), 'mime' => $invoice['mime']]);
                });
            } catch (\Throwable $e) {
                \Log::error('Invoice/email failed: '.$e->getMessage());
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
            \Log::error('Order placement failed: ' . $e->getMessage());
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
