<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Concerns\InteractsWithAuditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Category extends Model implements Sortable
{
    use HasFactory;
    use SortableTrait;
    use HasRecursiveRelationships;
    use InteractsWithAuditable;
    use SoftDeletes;

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

    public function buildSortQuery(): Builder
    {
        return static::query()->where('parent_id', $this->parent_id);
    }
}