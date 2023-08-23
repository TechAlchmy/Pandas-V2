<?php

namespace App\Models;

use App\Concerns\InteractsWithAuditable;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Order extends Model implements Sortable
{
    use HasFactory, SoftDeletes;
    use InteractsWithAuditable;
    use HasUuids;
    use SortableTrait;

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

    protected function orderNumber(): Attribute
    {
        return Attribute::get(fn ($value, $attributes) => data_get($attributes, 'order_column'));
    }

    public function uniqueIds()
    {
        return ['uuid'];
    }
}
