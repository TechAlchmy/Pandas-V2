<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountInsightModel extends Model
{
    use HasFactory;

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function discountInsight()
    {
        return $this->belongsTo(DiscountInsight::class);
    }
}
