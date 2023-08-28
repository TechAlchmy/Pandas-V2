<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Concerns\InteractsWithAuditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kirschbaum\PowerJoins\PowerJoins;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Category extends Model implements Sortable
{
    use HasFactory;
    use SortableTrait;
    use HasRecursiveRelationships;
    use InteractsWithAuditable;
    use SoftDeletes;
    use PowerJoins;
    use HasRelationships;

    protected $fillable = [
        'name',
        'link',
        'slug',
        'description',
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

    public function discountCategories()
    {
        return $this->hasMany(DiscountCategory::class);
    }

    public function discounts()
    {
        return $this->hasManyDeep(Discount::class, ['brand_categories', Brand::class]);
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'brand_categories')
            ->withTimestamps();
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

    public function buildSortQuery(): Builder
    {
        return static::query()->where('parent_id', $this->parent_id);
    }
}
