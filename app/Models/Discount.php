<?php

namespace App\Models;

use App\Concerns\InteractsWithAuditable;
use App\Enums\DiscountCallToActionEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use HasFactory;
    use InteractsWithAuditable;
    use SoftDeletes;

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
        'amounts' => 'array',
        'starts_at' => 'immutable_datetime',
        'ends_at' => 'immutable_datetime',
        'is_active' => 'boolean',
        'cta' => DiscountCallToActionEnum::class,
    ];

    public function discountCategories()
    {
        return $this->hasMany(DiscountCategory::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'discount_categories')
            ->withTimestamps();
    }

    public function discountRegions()
    {
        return $this->hasMany(DiscountRegion::class);
    }

    public function regions()
    {
        return $this->belongsToMany(Region::class, 'discount_regions')
            ->withTimestamps();
    }

    public function discountOffers()
    {
        return $this->hasMany(DiscountOffer::class);
    }

    public function discountTags()
    {
        return $this->hasMany(DiscountTag::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'discount_tags')
            ->withTimestamps();
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
