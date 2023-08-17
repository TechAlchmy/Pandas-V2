<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class FeaturedDeal extends Model implements Sortable
{
    use HasFactory;
    use SortableTrait;

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function buildSortQuery(): Builder
    {
        return static::query()->where('organization_id', $this->organization_id);
    }
}
