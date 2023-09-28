<?php

namespace App\Models;

use App\Concerns\InteractsWithAuditable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Squire\Models\Region;

class Organization extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use HasUuids;
    use InteractsWithAuditable;
    use InteractsWithMedia;

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function managers()
    {
        return $this->hasMany(Manager::class);
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'brand_organizations')
            ->withPivot(['is_active'])
            ->withTimestamps();
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function featuredDeals()
    {
        return $this->hasMany(FeaturedDeal::class);
    }

    public function featuredDiscounts()
    {
        return $this->belongsToMany(Discount::class, 'featured_deals')
            ->orderByPivot('order_column')
            ->withTimestamps();
    }

    public function offerTypes()
    {
        return $this->belongsToMany(OfferType::class, 'organization_offer_types')
            ->withPivot(['is_active'])
            ->withTimestamps();
    }

    public function voucherTypes()
    {
        return $this->belongsToMany(VoucherType::class, 'organization_voucher_types')
            ->withPivot(['is_active'])
            ->withTimestamps();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile();
    }

    public function uniqueIds()
    {
        return ['uuid'];
    }

    protected function registrationLink(): Attribute
    {
        return Attribute::get(fn () => route('register', ['organization_uuid' => $this->uuid]));
    }
}
