<?php

namespace App\Models;

use App\Concerns\InteractsWithAuditable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuids;
    use InteractsWithAuditable;

    protected $fillable = [
        'name',
        'website',
        'slug',
        'uniqid',
        'phone',
        'email',
        'user_id',
        'region_id',
    ];

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
}
