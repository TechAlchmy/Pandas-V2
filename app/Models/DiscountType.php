<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountType extends Model
{
    use HasFactory;
    protected $fillable = [
        'discount_id',
        'type_id',
    ];
    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
    public function offerType()
    {
        return $this->belongsTo(OfferType::class);
    }
}
