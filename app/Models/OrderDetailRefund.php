<?php

namespace App\Models;

use App\Concerns\InteractsWithAuditable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Znck\Eloquent\Traits\BelongsToThrough;

class OrderDetailRefund extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuids;
    use BelongsToThrough;
    use InteractsWithAuditable;

    protected $casts = [
        'approved_at' => 'immutable_datetime',
    ];

    public function order()
    {
        return $this->belongsToThrough(Order::class, OrderDetail::class);
    }

    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class);
    }

    public function discount()
    {
        return $this->belongsToThrough(Discount::class, OrderDetail::class);
    }

    public function brand()
    {
        return $this->belongsToThrough(Brand::class, [Discount::class, OrderDetail::class]);
    }

    public function user()
    {
        return $this->belongsToThrough(User::class, [Order::class, OrderDetail::class]);
    }

    public function uniqueIds()
    {
        return ['uuid'];
    }

    protected function statusMessage(): Attribute
    {
        return Attribute::get(function () {
            if ($this->trashed()) {
                return 'Rejected at ' . $this->deleted_at->format('d M Y');
            }

            if (! empty($this->approved_at)) {
                return 'Approved at ' . $this->approved_at->format('d M Y');
            }

            return 'In Review';
        });
    }
}
