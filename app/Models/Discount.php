<?php

namespace App\Models;

use App\Concerns\InteractsWithAuditable;
use App\Enums\DiscountVoucherTypeEnum;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kirschbaum\PowerJoins\PowerJoins;

class Discount extends Model
{
    use HasFactory;
    use InteractsWithAuditable;
    use SoftDeletes;
    use PowerJoins;

    protected $casts = [
        'amount' => 'array',
        'starts_at' => 'immutable_datetime',
        'ends_at' => 'immutable_datetime',
        'is_active' => 'boolean',
        'voucher_type' => DiscountVoucherTypeEnum::class,
    ];

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

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_details')
            ->withTimestamps();
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function brandCategories()
    {
        return $this->hasMany(BrandCategory::class, 'brand_id', 'brand_id');
    }

    public function offerTypes()
    {
        return $this->belongsToMany(OfferType::class, 'discount_types')
            ->withTimestamps();
    }

    public function featuredDeals()
    {
        return $this->hasMany(FeaturedDeal::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function scopeWithOfferTypes($query, $organization)
    {
        return $query->withWhereHas('offerTypes', function ($query) use ($organization) {
            $query->whereHas('organizationOfferTypes', function ($query) use ($organization) {
                $query->where('is_active', true)
                    ->where('organization_id', $organization?->getKey());
            });
        });
    }

    public function scopeWithBrand($query, $organization)
    {
        return $query->withWhereHas('brand', function ($query) use ($organization) {
            $query
                ->with('media')
                ->where('is_active', true)
                ->forOrganization($organization);
        });
    }

    public function scopeWithVoucherType($query, $organization)
    {
        return $query->when($organization, function ($query, $organization) {
            $query->whereIn('voucher_type', $organization->voucher_types);
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now());
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false)
            ->orWhere('starts_at', '>=', now())
            ->orWhere('ends_at', '<=', now());
    }

    protected function isAmountSingle(): Attribute
    {
        return Attribute::get(fn () => count($this->amount) == 1);
    }

    protected function moneyAmount(): Attribute
    {
        return Attribute::get(fn () => collect($this->amount)
            ->map(fn ($amount) => Money::ofMinor($amount, 'USD'))
            ->all());
    }

    protected function moneyLimitAmount(): Attribute
    {
        return Attribute::get(fn () => is_null($this->limit_amount)
            ? null
            : Money::ofMinor($this->limit_amount, 'USD'));
    }

    protected function cta(): Attribute
    {
        return Attribute::get(fn () => $this->cta_text ?? $this->voucher_type->getLabel());
    }
}
