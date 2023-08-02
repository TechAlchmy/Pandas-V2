<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'link',
        'slug',
        'uniqid',
        'description',
        'logo',
        'views',
        'status',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'starts_at' => 'immutable_datetime',
        'ends_at' => 'immutable_datetime',
        'is_active' => 'boolean',
    ];

    public function discountCategories()
    {
        return $this->hasMany(DiscountCategory::class);
    }

    public function discountRegions()
    {
        return $this->hasMany(DiscountRegion::class);
    }

    public function discountOffers()
    {
        return $this->hasMany(DiscountOffer::class);
    }

    public function discountTags()
    {
        return $this->hasMany(DiscountTag::class);
    }

    public function offerType()
    {
        return $this->belongsTo(OfferType::class);
    }

    public function voucherType()
    {
        return $this->belongsTo(VoucherType::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
