<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountRegion extends Model
{
    use HasFactory;

    protected $fillable = [
        'discount_id',
        'region_id',
    ];

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
