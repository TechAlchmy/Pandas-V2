<?php

namespace App\Models;

use App\Concerns\InteractsWithAuditable;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class OrderRefund extends Model implements Sortable
{
    use HasFactory;
    use HasUuids;
    use InteractsWithAuditable;
    use SortableTrait;

    protected $casts = [
        'approved_at' => 'immutable_datetime',
        'amount' => 'integer',
        'actual_amount' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class);
    }

    public function uniqueIds()
    {
        return ['uuid'];
    }

    protected function moneyAmount(): Attribute
    {
        return Attribute::get(fn () => Money::ofMinor($this->amount, 'USD'));
    }

    protected function moneyActualAmount(): Attribute
    {
        return Attribute::get(fn () => Money::ofMinor($this->actual_amount, 'USD'));
    }
}
