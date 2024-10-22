<?php

namespace App\Models;

use App\Concerns\InteractsWithAuditable;
use App\Services\BlackHawkService;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Kirschbaum\PowerJoins\PowerJoins;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Brand extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithAuditable;
    use SoftDeletes;
    use PowerJoins;
    use HasUuids;
    use InteractsWithMedia;

    protected $casts = [
        'is_active' => 'boolean',
        'region_ids' => 'array',
    ];

    public function brandCategories()
    {
        return $this->hasMany(BrandCategory::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'brand_categories')
            ->withTimestamps();
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    public function brandOrganizations()
    {
        return $this->hasMany(BrandOrganization::class);
    }

    public function brandOrganization()
    {
        return $this->brandOrganizations()->one();
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'brand_organizations')
            ->withTimestamps();
    }

    public function scopeNeedsAttention($query)
    {
        return $query->where('link', 'like', '%' . BlackHawkService::DUMMY_URL_PREFIX . '%');
    }

    public function scopeDoesntNeedsAttention($query)
    {
        return $query->where('link', 'not like', '%' . BlackHawkService::DUMMY_URL_PREFIX . '%');
    }

    public function scopeForOrganization($query, $organization)
    {
        return $query->when($organization, function ($query, $organization) {
            $query->whereIn('brands.id', BrandOrganization::query()
                ->select('brand_id')
                ->where('is_active', true)
                ->whereBelongsTo($organization));

            $query->where(function ($query) use ($organization) {
                $query->where('region_ids', 'like', "%{$organization->region_id}%");
            });
        });
    }

    public function uniqueIds()
    {
        return ['uuid'];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile();
    }
}
