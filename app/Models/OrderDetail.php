<?php

namespace App\Models;

use Brick\Money\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class OrderDetail extends Model implements Sortable
{
    use HasFactory;
    use SortableTrait;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function buildSortQuery(): Builder
    {
        return static::query()->where('order_id', $this->order_id);
    }

    protected function amountSubPublicPercentage(): Attribute
    {
        return Attribute::get(fn () => $this->amount - ($this->amount * $this->public_percentage));
    }

    protected function amountSubPercentage(): Attribute
    {
        return Attribute::get(fn () => $this->amount - ($this->amount * $this->percentage));
    }

    protected function totalPublic(): Attribute
    {
        return Attribute::get(fn () => $this->quantity * $this->amount_sub_public_percentage);
    }

    protected function total(): Attribute
    {
        return Attribute::get(fn () => $this->quantity * $this->amount_sub_percentage);
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
