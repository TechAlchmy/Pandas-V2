<?php

namespace App\Models;

use App\Concerns\InteractsWithAuditable;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Brick\Money\Money;
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

    public function orderDetailRefunds()
    {
        return $this->hasManyThrough(OrderDetailRefund::class, OrderDetail::class);
    }

    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'order_details');
    }

    protected function orderNumber(): Attribute
    {
        return Attribute::get(fn ($value, $attributes) => data_get($attributes, 'order_column'));
    }

    protected function moneyOrderTotal(): Attribute
    {
        return Attribute::get(fn () => Money::ofMinor($this->order_total, 'USD'));
    }

    protected function moneyOrderSubtotal(): Attribute
    {
        return Attribute::get(fn () => Money::ofMinor($this->order_subtotal, 'USD'));
    }

    protected function moneyOrderTax(): Attribute
    {
        return Attribute::get(fn () => Money::ofMinor($this->order_tax, 'USD'));
    }

    protected function moneyOrderDiscount(): Attribute
    {
        return Attribute::get(fn () => Money::ofMinor($this->order_discount, 'USD'));
    }

    public function uniqueIds()
    {
        return ['uuid'];
    }
}
