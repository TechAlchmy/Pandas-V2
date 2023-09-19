<?php

namespace App\Models;

use App\Concerns\InteractsWithAuditable;
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

    public function order()
    {
        return $this->belongsToThrough(Order::class, OrderDetail::class);
    }

    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class);
    }

    public function uniqueIds()
    {
        return ['uuid'];
    }
}
