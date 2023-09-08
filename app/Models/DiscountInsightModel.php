<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class DiscountInsightModel extends Model implements Sortable
{
    use HasFactory;
    use SortableTrait;

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function discountInsight()
    {
        return $this->belongsTo(DiscountInsight::class);
    }

    public function buildSortQuery(): Builder
    {
        return static::query()->where('discount_insight_id', $this->discount_insight_id);
    }
}
