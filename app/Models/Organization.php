<?php

namespace App\Models;

use App\Concerns\InteractsWithAuditable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Squire\Models\Region;

class Organization extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuids;
    use InteractsWithAuditable;

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

    public function uniqueIds()
    {
        return ['uuid'];
    }

    protected function registrationLink(): Attribute
    {
        return Attribute::get(fn () => route('register', ['organization_uuid' => $this->uuid]));
    }
}
