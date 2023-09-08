<?php

namespace App\Models;

use App\Concerns\InteractsWithAuditable;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRefund extends Model
{
    use HasFactory;
    use InteractsWithAuditable;

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

    protected function moneyAmount(): Attribute
    {
        return Attribute::get(fn () => Money::ofMinor($this->amount, 'USD'));
    }

    protected function moneyActualAmount(): Attribute
    {
        return Attribute::get(fn () => Money::ofMinor($this->actual_amount, 'USD'));
    }
}
