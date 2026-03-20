<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $fillable = [
        'order_id',
        'payment_method',
        'total_amount',
        'paid_amount',
        'change_amount',
        'paid_at',
        'status',
        'khqr_md5',
        'khqr_string',
        'khqr_transaction_id',
        'khqr_expires_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'khqr_expires_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Check if payment is KHQR type
     */
    public function isKHQR(): bool
    {
        return $this->payment_method === 'khqr';
    }

    /**
     * Check if KHQR payment is pending
     */
    public function isKHQRPending(): bool
    {
        return $this->isKHQR() && $this->status === 'pending';
    }

    /**
     * Check if KHQR has expired
     */
    public function isKHQRExpired(): bool
    {
        return $this->isKHQR() && $this->khqr_expires_at && $this->khqr_expires_at->isPast();
    }
}
