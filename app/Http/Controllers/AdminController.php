<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    public function index(){
        // Get actual counts from database
        $totalProducts = Product::count();
        $totalBrands = Brand::count();
        $totalCategories = Category::count();
        $totalUsers = \App\Models\User::count();
        
        // Get order-related counts from database
        $totalOrders = \App\Models\Order::count();
        $pendingOrders = \App\Models\Order::where('status', 'ordered')->count();
        $deliveredOrders = \App\Models\Order::where('status', 'delivered')->count();
        $canceledOrders = \App\Models\Order::where('status', 'canceled')->count();
        $totalAmount = \App\Models\Order::sum('total');
        $pendingAmount = \App\Models\Order::where('status', 'ordered')->sum('total');
        $deliveredAmount = \App\Models\Order::where('status', 'delivered')->sum('total');
        $canceledAmount = \App\Models\Order::where('status', 'canceled')->sum('total');
        
        return view('admin.index', compact(
            'totalProducts', 'totalBrands', 'totalCategories', 'totalUsers',
            'totalOrders', 'pendingOrders', 'deliveredOrders', 'canceledOrders',
            'totalAmount', 'pendingAmount', 'deliveredAmount', 'canceledAmount'
        ));
    }
    public function brands(){
        $brands = Brand::withCount('products')->orderBy('id','DESC')->paginate(10);
        return view('admin.brands',compact('brands'));
    }

    public function add_brand(){
        return view('admin.brand-add');
    }
    public function brand_store(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'

        ]);
        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $image = $request->file('image');
        $file_extension =  $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp.'.'.$file_extension;
        $this->GenerateBrandThumbailsImage($image,$file_name);
        $brand->image = $file_name;
        $brand->save();
        return redirect()->route('admin.brands')->with('status','Brand has been added successfully');
    }

    public function brand_edit($id){
        $brand = Brand::find($id);
        return view('admin.brand-edit',compact('brand'));
    }

    public function brand_update(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|unique:brands,slug,' . $request->id,
        'image' => 'nullable|mimes:png,jpg,jpeg|max:2048'
    ]);

    $brand = Brand::find($request->id);

    if (!$brand) {
        return redirect()->back()->with('error', 'Brand not found');
    }

    $brand->name = $request->name;
    $brand->slug = Str::slug($request->name);

    if ($request->hasFile('image')) {
        if ($brand->image && File::exists(public_path('uploads/brands/'.$brand->image))) {
            File::delete(public_path('uploads/brands/'.$brand->image));
        }

        $image = $request->file('image');
        $file_extension = $image->extension();
        $file_name = Carbon::now()->timestamp.'.'.$file_extension;

        $this->GenerateBrandThumbailsImage($image, $file_name);

        $brand->image = $file_name;
    }

    $brand->save();

    return redirect()->route('admin.brands')->with('status', 'Brand has been updated successfully');
}


    public function GenerateBrandThumbailsImage($image,$imageName){
        $destinationPath = public_path('uploads/brands');
        $img = Image::read($image->path());
        $img->cover(124,124,"top");
        $img->resize(124,124,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);
    }
    public function brand_delete($id){
        $brand = Brand::find($id);
        if(File::exists(public_path('uploads/brands').'/'.$brand->image)){
            File::delete(public_path('uploads/brands').'/'.$brand->image);
        }
        $brand->delete();
        return redirect()->route('admin.brands')->with('status','Brand has been deleted successfully!');
    }
    public function categories(){
        $categories = Category::withCount('products')->orderBy('id','DESC')->paginate(10);
        return view('admin.categories',compact('categories'));
    }
    public function category_add(){
        return view('admin.category-add');
    }
    public function category_store(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'

        ]);
        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $image = $request->file('image');
        $file_extension =  $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp.'.'.$file_extension;
        $this->GenerateCategoryThumbailsImage($image,$file_name);
        $category->image = $file_name;
        $category->save();
        return redirect()->route('admin.categories')->with('status','Category has been added successfully');
    }

    public function GenerateCategoryThumbailsImage($image,$imageName){
        $destinationPath = public_path('uploads/categories');
        $img = Image::read($image->path());
        $img->cover(124,124,"top");
        $img->resize(124,124,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);
    }

    public function category_edit($id){
        $category = Category::find($id);
        return view('admin.category-edit',compact('category'));
    }

    public function category_update(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|unique:categories,slug,' . $request->id,
        'image' => 'nullable|mimes:png,jpg,jpeg|max:2048'
    ]);
    $category = Category::find($request->id);

    if (!$category) {
        return redirect()->back()->with('error', 'Brand not found');
    }

    $category->name = $request->name;
    $category->slug = Str::slug($request->name);

    if ($request->hasFile('image')) {
        if ($category->image && File::exists(public_path('uploads/categories/'.$category->image))) {
            File::delete(public_path('uploads/categories/'.$category->image));
        }

        $image = $request->file('image');
        $file_extension = $image->extension();
        $file_name = Carbon::now()->timestamp.'.'.$file_extension;

        $this->GenerateCategoryThumbailsImage($image, $file_name);

        $category->image = $file_name;
    }

    $category->save();

    return redirect()->route('admin.categories')->with('status', 'Category has been updated successfully');

    }

    public function category_delete($id){
        $category = Category::find($id);
        if(File::exists(public_path('uploads/categories').'/'.$category->image)){
            File::delete(public_path('uploads/categories').'/'.$category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories')->with('status','Category has been deleted successfully!');
    }

    public function products(Request $request)
    {
        $query = Product::with(['category', 'brand']);

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('SKU', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply stock status filter
        if ($request->filled('stock_status')) {
            $query->where('stock_status', $request->stock_status);
        }

        // Apply featured filter
        if ($request->filled('featured')) {
            $query->where('featured', $request->featured);
        }

        $products = $query->OrderBy('created_at','DESC')->paginate(10);
        
        return view('admin.products',compact('products'));
    }

    public function product_add()
    {
    $categories = Category::select('id','name')->orderBy('name')->get();
    $brands = Brand::select('id','name')->orderBy('name')->get();
    return view('admin.product-add', compact('categories','brands'));
    }

    public function product_store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'slug' => 'required|unique:products,slug',          
        'short_description' => 'required',
        'description' => 'required',
        'regular_price' => 'required',
        'sale_price' => 'required',
        'SKU' => 'required',
        'stock_status' => 'required',
        'featured' => 'required',
        'quantity' => 'required',
        'image' => 'required|mimes:png,jpg,jpeg|max:2048',   
        'category_id' => 'required',
        'brand_id' => 'required',            
    ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        

        $current_timestamp = Carbon::now()->timestamp;

        // Main product image
        if($request->hasFile('image'))
        {        
            if ($product->image && File::exists(public_path('uploads/products/'.$product->image))) {
                File::delete(public_path('uploads/products/'.$product->image));
            }
            if ($product->image && File::exists(public_path('uploads/products/thumbnails/'.$product->image))) {
                File::delete(public_path('uploads/products/thumbnails/'.$product->image));
            }            

            $image = $request->file('image');
            $imageName = $current_timestamp.'.'.$image->extension();
            $this->GenerateProductThumbnailImage($image, $imageName);            
            $product->image = $imageName;
        }

        // Gallery images
        $gallery_arr = array();
        $gallery_images = '';
        $counter = 1;

        if($request->hasFile('images'))
        {
            if ($product->images) {
                $oldGImages = explode(',', $product->images);
                foreach($oldGImages as $gimage)
                {
                    if (File::exists(public_path('uploads/products/'.trim($gimage)))) {
                        File::delete(public_path('uploads/products/'.trim($gimage)));
                    }
                    if (File::exists(public_path('uploads/products/thumbnails/'.trim($gimage)))) {
                        File::delete(public_path('uploads/products/thumbnails/'.trim($gimage)));
                    }
                }
            }

            $allowedfileExtension = ['jpg','png','jpeg'];
            $files = $request->file('images');
            foreach($files as $file)
            {                
                $gextension = $file->getClientOriginalExtension();                                
                if(in_array($gextension, $allowedfileExtension))
                {
                    $gfilename = $current_timestamp . '-' . $counter . '.' . $gextension;   
                    $this->GenerateProductThumbnailImage($file, $gfilename);                    
                    array_push($gallery_arr,$gfilename);
                    $counter++;
                }
            }
            $gallery_images = implode(',', $gallery_arr);
        }

        $product->images = $gallery_images;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id; 
        $product->save();
        return redirect()->route('admin.products')->with('status','Record has been added successfully!');
    }

public function GenerateProductThumbnailImage($image,$imageName){
        $destinationPathThumbnail = public_path('uploads/products/thumbnails');
        $destinationPath = public_path('uploads/products');
        $img = Image::read($image->path());

        $img->cover(540,689,"top");
        $img->resize(540,689,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);

        $img->resize(104,104,function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPathThumbnail.'/'.$imageName);
    }

    public function product_edit($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return redirect()->route('admin.products')->with('error', 'Product not found');
        }
        $categories = Category::select('id','name')->orderBy('name')->get();
        $brands = Brand::select('id','name')->orderBy('name')->get();
        return view('admin.product-edit', compact('product', 'categories', 'brands'));
    }

    public function product_update(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:products,id',
                'name' => 'required|string|max:255',
                'slug' => 'required|unique:products,slug,' . $request->id,
                'short_description' => 'required',
                'description' => 'required',
                'regular_price' => 'required|numeric|min:0',
                'sale_price' => 'required|numeric|min:0',
                'SKU' => 'required|string|max:255',
                'stock_status' => 'required|in:in_stock,out_of_stock',
                'featured' => 'required|in:0,1',
                'quantity' => 'required|integer|min:0',
                'image' => 'nullable|mimes:png,jpg,jpeg|max:2048',
                'images.*' => 'nullable|mimes:png,jpg,jpeg|max:2048',
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'required|exists:brands,id',
            ]);

            $product = Product::find($request->id);
            if (!$product) {
                return redirect()->back()->with('error', 'Product not found');
            }

            // Log the values being updated for debugging
            \Log::info('Updating product', [
                'id' => $request->id,
                'stock_status' => $request->stock_status,
                'featured' => $request->featured,
                'quantity' => $request->quantity
            ]);

            $product->name = $request->name;
            $product->slug = Str::slug($request->name);
            $product->short_description = $request->short_description;
            $product->description = $request->description;
            $product->regular_price = $request->regular_price;
            $product->sale_price = $request->sale_price;
            $product->SKU = $request->SKU;
            $product->stock_status = $request->stock_status;
            $product->featured = $request->featured;
            $product->quantity = $request->quantity;

            $current_timestamp = Carbon::now()->timestamp;

            // Update main product image
            if($request->hasFile('image')) {
                // Delete old image
                if ($product->image && File::exists(public_path('uploads/products/'.$product->image))) {
                    File::delete(public_path('uploads/products/'.$product->image));
                }
                if ($product->image && File::exists(public_path('uploads/products/thumbnails/'.$product->image))) {
                    File::delete(public_path('uploads/products/thumbnails/'.$product->image));
                }

                $image = $request->file('image');
                $imageName = $current_timestamp.'.'.$image->extension();
                $this->GenerateProductThumbnailImage($image, $imageName);
                $product->image = $imageName;
            } elseif ($request->input('remove_main_image') == '1') {
                // Remove main image if requested
                if ($product->image && File::exists(public_path('uploads/products/'.$product->image))) {
                    File::delete(public_path('uploads/products/'.$product->image));
                }
                if ($product->image && File::exists(public_path('uploads/products/thumbnails/'.$product->image))) {
                    File::delete(public_path('uploads/products/thumbnails/'.$product->image));
                }
                $product->image = null;
            }

            // Update gallery images
            if($request->hasFile('images')) {
                // Delete old gallery images
                if ($product->images) {
                    $oldGImages = explode(',', $product->images);
                    foreach($oldGImages as $gimage) {
                        if (File::exists(public_path('uploads/products/'.trim($gimage)))) {
                            File::delete(public_path('uploads/products/'.trim($gimage)));
                        }
                        if (File::exists(public_path('uploads/products/thumbnails/'.trim($gimage)))) {
                            File::delete(public_path('uploads/products/thumbnails/'.trim($gimage)));
                        }
                    }
                }

                $gallery_arr = array();
                $counter = 1;
                $allowedfileExtension = ['jpg','png','jpeg'];
                $files = $request->file('images');
                
                foreach($files as $file) {
                    $gextension = $file->getClientOriginalExtension();
                    if(in_array($gextension, $allowedfileExtension)) {
                        $gfilename = $current_timestamp . '-' . $counter . '.' . $gextension;
                        $this->GenerateProductThumbnailImage($file, $gfilename);
                        array_push($gallery_arr, $gfilename);
                        $counter++;
                    }
                }
                $product->images = implode(',', $gallery_arr);
            }

            $product->category_id = $request->category_id;
            $product->brand_id = $request->brand_id;
            
            $saved = $product->save();
            
            if ($saved) {
                \Log::info('Product updated successfully', ['id' => $product->id]);
                return redirect()->route('admin.products')->with('status', 'Product has been updated successfully!');
            } else {
                \Log::error('Failed to save product', ['id' => $product->id]);
                return redirect()->back()->with('error', 'Failed to update product. Please try again.');
            }
            
        } catch (\Exception $e) {
            \Log::error('Error updating product: ' . $e->getMessage(), [
                'product_id' => $request->id ?? 'unknown',
                'request_data' => $request->except(['image', 'images'])
            ]);
            
            return redirect()->back()->with('error', 'An error occurred while updating the product: ' . $e->getMessage());
        }
    }

    public function product_delete($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return redirect()->route('admin.products')->with('error', 'Product not found');
        }

        // Delete main product image
        if ($product->image && File::exists(public_path('uploads/products/'.$product->image))) {
            File::delete(public_path('uploads/products/'.$product->image));
        }
        if ($product->image && File::exists(public_path('uploads/products/thumbnails/'.$product->image))) {
            File::delete(public_path('uploads/products/thumbnails/'.$product->image));
        }

        // Delete gallery images
        if ($product->images) {
            $galleryImages = explode(',', $product->images);
            foreach($galleryImages as $gimage) {
                if (File::exists(public_path('uploads/products/'.trim($gimage)))) {
                    File::delete(public_path('uploads/products/'.trim($gimage)));
                }
                if (File::exists(public_path('uploads/products/thumbnails/'.trim($gimage)))) {
                    File::delete(public_path('uploads/products/thumbnails/'.trim($gimage)));
                }
            }
        }

        $product->delete();
        return redirect()->route('admin.products')->with('status', 'Product has been deleted successfully!');
    }

    public function shop(Request $request)
    {
        $query = Product::with(['category', 'brand']);

        // Apply category filter if specified
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Apply brand filter if specified
        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        // Apply search filter if specified
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('SKU', 'like', "%{$search}%");
            });
        }

        // Apply stock status filter if specified
        if ($request->filled('stock_status')) {
            $query->where('stock_status', $request->stock_status);
        }

        // Apply featured filter if specified
        if ($request->filled('featured')) {
            $query->where('featured', $request->featured);
        }

        $products = $query->orderBy('featured', 'DESC')
                         ->orderBy('created_at', 'DESC')
                         ->paginate(12);
        
        // Get categories with product counts
        $categories = Category::withCount('products')->orderBy('name')->get();
        
        // Get brands with product counts
        $brands = Brand::withCount('products')->orderBy('name')->get();
        
        // Get shop statistics
        $shopStats = [
            'total_products' => Product::count(),
            'in_stock_products' => Product::where('stock_status', 'in_stock')->count(),
            'out_of_stock_products' => Product::where('stock_status', 'out_of_stock')->count(),
            'featured_products' => Product::where('featured', 1)->count(),
            'total_categories' => Category::count(),
            'total_brands' => Brand::count(),
            'low_stock_products' => Product::where('quantity', '<=', 5)->where('quantity', '>', 0)->count(),
            'new_arrivals' => Product::where('created_at', '>=', now()->subDays(30))->count()
        ];
        
        return view('admin.shop', compact('products', 'categories', 'brands', 'shopStats'));
    }

    public function productDetails($id)
    {
        $product = Product::with(['category', 'brand'])
            ->findOrFail($id);
        
        // Get related products from same category
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['category', 'brand'])
            ->take(4)
            ->get();
        
        return view('admin.product-details', compact('product', 'relatedProducts'));
    }

    // Coupon Methods
    public function coupons()
    {
        $coupons = Coupon::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.coupons', compact('coupons'));
    }

    public function add_coupon()
    {
        return view('admin.coupon-add');
    }

    public function store_coupon(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons,code|string|max:50',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'cart_value' => 'required|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'expiry_date' => 'required|date|after:today',
        ]);

        if ($request->type === 'percent' && $request->value > 100) {
            return redirect()->back()->withErrors(['value' => 'Percentage value cannot exceed 100%']);
        }

        Coupon::create([
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
            'cart_value' => $request->cart_value,
            'max_uses' => $request->max_uses,
            'expiry_date' => $request->expiry_date,
            'is_active' => true,
        ]);

        return redirect()->route('admin.coupons')->with('status', 'Coupon has been added successfully!');
    }

    public function edit_coupon($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupon-edit', compact('coupon'));
    }

    public function update_coupon(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:coupons,id',
            'code' => 'required|string|max:50|unique:coupons,code,' . $request->id,
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'cart_value' => 'required|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'expiry_date' => 'required|date|after:today',
        ]);

        if ($request->type === 'percent' && $request->value > 100) {
            return redirect()->back()->withErrors(['value' => 'Percentage value cannot exceed 100%']);
        }

        $coupon = Coupon::findOrFail($request->id);
        $coupon->update([
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
            'cart_value' => $request->cart_value,
            'max_uses' => $request->max_uses,
            'expiry_date' => $request->expiry_date,
        ]);

        return redirect()->route('admin.coupons')->with('status', 'Coupon has been updated successfully!');
    }

    public function delete_coupon($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return redirect()->route('admin.coupons')->with('status', 'Coupon has been deleted successfully!');
    }

    public function toggle_coupon_status($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->update(['is_active' => !$coupon->is_active]);
        
        $status = $coupon->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.coupons')->with('status', "Coupon has been {$status} successfully!");
    }

    // Order Methods
    public function orders(Request $request)
    {
        $query = \App\Models\Order::with(['user', 'shippingAddress', 'items.product.category', 'items.product.brand']);
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('shippingAddress', function($addressQuery) use ($search) {
                      $addressQuery->where('phone', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        $orders = $query->orderBy('created_at', 'DESC')->paginate(15);
        return view('admin.orders', compact('orders'));
    }

    public function order_details($id)
    {
        $order = \App\Models\Order::with(['user', 'shippingAddress', 'items.product.category', 'items.product.brand'])
                                  ->findOrFail($id);
        return view('admin.order-details', compact('order'));
    }

    public function update_order_status(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'order_status' => 'required|in:ordered,delivered,canceled'
        ]);

        $order = \App\Models\Order::findOrFail($request->order_id);
        $order->status = $request->order_status;
        
        if($request->order_status == 'delivered') {
            $order->delivered_date = \Carbon\Carbon::now();
            $order->payment_status = 'paid';
        }
        else if($request->order_status == 'canceled') {
            $order->canceled_date = \Carbon\Carbon::now();
            $order->payment_status = 'refunded';
        }
        
        $order->save();
        
        return redirect()->back()->with('status', 'Status changed successfully!');
    }
}
