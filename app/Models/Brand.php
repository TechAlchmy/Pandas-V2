<?php

namespace App\Models;

use App\Concerns\InteractsWithAuditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory;
    use InteractsWithAuditable;

    protected $fillable = [
        'name',
        'link',
        'slug',
        'uniqid',
        'description',
        'logo',
        'views',
        'status',
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

    public function regions()
    {
        return $this->belongsToMany(Region::class, 'brand_regions')
            ->withTimestamps();
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value);
    }
}
