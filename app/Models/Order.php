<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    //
    protected $fillable = [
        'order_no',
        'customer_id', // nullable for walk-in customers or takeout orders without a registered customer
        'table_id', // nullable for takeout orders
        'order_type', // dine-in, takeout, delivery
        'user_id',
        'status', 
        // New fields for totals
        'notes',
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

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function menuItemRatings(): HasMany
    {
        return $this->hasMany(MenuItemRating::class);
    }

   
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the payment method from the first payment
     */
    public function getPaymentMethodAttribute()
    {
        $payment = $this->payments->first();
        if (!$payment) {
            return __('app.unpaid');
        }

        // For delivery orders with cash payment that's still pending (COD)
        if ($this->order_type === 'delivery' && $payment->payment_method === 'cash' && $payment->status === 'pending') {
            return __('app.cash_pending');
        }

        // Return translated method name if available, otherwise formatted method name
        return match($payment->payment_method) {
            'cash' => __('app.payment_cash') ?? 'Cash',
            'card' => __('app.payment_card') ?? 'Card',
            'khqr' => __('app.payment_khqr') ?? 'KHQR',
            'aba'  => __('app.payment_aba')  ?? 'ABA',
            'qr'   => __('app.payment_qr')   ?? 'QR Code',
            default => ucwords(str_replace('_', ' ', $payment->payment_method))
        };
    }

    /**
     * Get the first payment record
     */
    public function getPaymentAttribute()
    {
        return $this->payments->first();
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

    /**
     * Get count of orders by status
     */
    public static function getStatusCounts(): array
    {
        return [
            'pending' => self::where('status', 'pending')->count(),
            'confirmed' => self::where('status', 'confirmed')->count(),
            'delivered' => self::where('status', 'delivered')->count(),
            'completed' => self::where('status', 'completed')->count(),
            'refunded' => self::where('status', 'refunded')->count(),
            'cancelled' => self::where('status', 'cancelled')->count(),
        ];
    }

}
