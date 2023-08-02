<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_ON_HOLD = 'on hold';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_FAILED = 'failed';
    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_FAILED = 'failed';
    const PAYMENT_STATUS_REFUNDED = 'refunded';
        
    
    

    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'order_number',
        'payment_method',
        'payment_status',
        'amount',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
