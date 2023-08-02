<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class BrandRegion extends Model
{
    use HasFactory;
    protected $fillable = [
        'brand_id',
        'region_id',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
