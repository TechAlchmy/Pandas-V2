<?php

namespace App\Models;

use App\Concerns\InteractsWithAuditable;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, SoftDeletes;
    use InteractsWithAuditable;
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'payment_status' => PaymentStatus::class,
        'order_status' => OrderStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    {
    }

    public function uniqueIds()
    {
        return ['uuid'];
    }
}
