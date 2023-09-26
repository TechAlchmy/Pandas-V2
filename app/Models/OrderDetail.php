<?php

namespace App\Models;

use Brick\Money\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Znck\Eloquent\Traits\BelongsToThrough;

class OrderDetail extends Model implements Sortable
{
    use HasFactory;
    use BelongsToThrough;
    use SortableTrait;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderDetailRefund()
    {
        return $this->hasOne(OrderDetailRefund::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function brand()
    {
        return $this->belongsToThrough(Brand::class, Discount::class);
    }

    public function buildSortQuery(): Builder
    {
        return static::query()->where('order_id', $this->order_id);
    }

    protected function subtotal(): Attribute
    {
        return Attribute::get(fn () => $this->quantity * $this->amount);
    }

    protected function discountPublic(): Attribute
    {
        return Attribute::get(fn () => (int) \round($this->subtotal * ($this->public_percentage / 100 / 100)));
    }

    protected function discountInternal(): Attribute
    {
        return Attribute::get(fn () => (int) \round($this->subtotal * ($this->percentage / 100 / 100)));
    }

    protected function totalInternal(): Attribute
    {
        return Attribute::get(fn () => $this->subtotal - $this->discount_internal);
    }

    protected function total(): Attribute
    {
        return Attribute::get(fn () => $this->subtotal - $this->discount_public);
    }

    protected function moneyTotal(): Attribute
    {
        return Attribute::get(fn () => $this->money_amount->multipliedBy($this->quantity));
    }

    protected function moneyAmount(): Attribute
    {
        return Attribute::get(fn () => Money::ofMinor($this->amount, 'USD'));
    }
}
