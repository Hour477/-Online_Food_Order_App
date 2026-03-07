<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class Order extends Model
{
    //
    protected $fillable = [
        'order_no',
        'customer_id', // nullable for walk-in customers or takeout orders without a registered customer
        'table_id', // nullable for takeout orders
        'order_type', // dine-in, takeout, delivery
        'user_id',
        'status', // pending, preparing, ready, completed, cancelled
        // New fields for totals
        'subtotal',
        'tax',
        'total_amount',
        'created_at',
        'updated_at',
    ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Backwards-compatible alias used by views: $order->items
     */
    public function getItemsAttribute()
    {
        return $this->orderItems;
    }

    /**
     * The user who created the order (optional)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    

    /* =========================
    CART CALCULATIONS
    ========================= */

    public function getSubtotalAttribute()
    {
        return $this->orderItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    public function getTaxAttribute()
    {
        return $this->subtotal * 0.10; // 10% tax
    }

    public function getGrandTotalAttribute()
    {
        return $this->subtotal + $this->tax;
    }

    
 
}
