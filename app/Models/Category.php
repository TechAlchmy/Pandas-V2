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

    public function scopeWithBrands($query, $organization, $sort = null)
    {
        return $query->withWhereHas('brands', function ($query) use ($organization, $sort) {
            $query
                ->with('media')
                ->where('is_active', true)
                ->when($sort, fn ($query, $sort) => match ($sort) {
                    default => $query->orderBy(Brand::select('views')
                        ->whereColumn('brand_categories.brand_id', 'brands.id')
                        ->latest()
                        ->take(1), 'desc'),
                })
                ->forOrganization($organization);
        });
    }

    public function buildSortQuery(): Builder
    {
        return static::query()->where('parent_id', $this->parent_id);
    }
}
