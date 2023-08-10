<?php

namespace App\Models;

use App\Concerns\InteractsWithAuditable;
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

    protected $fillable = [
        'name',
        'link',
        'slug',
        'uniqid',
        'description',
        'logo',
        'views',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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

    public function brandRegions()
    {
        return $this->hasMany(BrandRegion::class);
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    public function regions()
    {
        return $this->belongsToMany(Region::class, 'brand_regions')
            ->withTimestamps();
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

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value);
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
