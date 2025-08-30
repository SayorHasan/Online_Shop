<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'short_description', 'description', 'regular_price', 
        'sale_price', 'SKU', 'stock_status', 'featured', 'quantity', 
        'image', 'images', 'category_id', 'brand_id'
    ];

    protected $casts = [
        'regular_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'quantity' => 'integer',
        'featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }  
    
    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id');
    }

    public function getGalleryAttribute()
    {
        if ($this->images) {
            return explode(',', $this->images);
        }
        return [];
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Check if product is in stock
     */
    public function isInStock()
    {
        return $this->quantity > 0 && $this->stock_status === 'in_stock';
    }

    /**
     * Check if product has enough stock for given quantity
     */
    public function hasStock($quantity)
    {
        return $this->quantity >= $quantity;
    }

    /**
     * Reduce stock quantity
     */
    public function reduceStock($quantity)
    {
        if ($this->quantity >= $quantity) {
            $this->quantity -= $quantity;
            
            // Update stock status if quantity becomes 0
            if ($this->quantity <= 0) {
                $this->stock_status = 'out_of_stock';
                $this->quantity = 0;
            }
            
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Increase stock quantity
     */
    public function increaseStock($quantity)
    {
        $this->quantity += $quantity;
        
        // Update stock status if quantity becomes available
        if ($this->quantity > 0 && $this->stock_status === 'out_of_stock') {
            $this->stock_status = 'in_stock';
        }
        
        $this->save();
        return true;
    }

    /**
     * Set stock quantity
     */
    public function setStock($quantity)
    {
        $this->quantity = max(0, $quantity);
        
        // Update stock status based on quantity
        if ($this->quantity <= 0) {
            $this->stock_status = 'out_of_stock';
            $this->quantity = 0;
        } else {
            $this->stock_status = 'in_stock';
        }
        
        $this->save();
        return true;
    }

    /**
     * Get available stock quantity
     */
    public function getAvailableStock()
    {
        return $this->quantity;
    }

    /**
     * Get stock status text
     */
    public function getStockStatusText()
    {
        if ($this->quantity <= 0) {
            return 'Out of Stock';
        } elseif ($this->quantity <= 5) {
            return 'Low Stock (' . $this->quantity . ' left)';
        } else {
            return 'In Stock (' . $this->quantity . ' available)';
        }
    }

    /**
     * Get stock status badge class
     */
    public function getStockStatusBadgeClass()
    {
        if ($this->quantity <= 0) {
            return 'badge-danger';
        } elseif ($this->quantity <= 5) {
            return 'badge-warning';
        } else {
            return 'badge-success';
        }
    }
}
