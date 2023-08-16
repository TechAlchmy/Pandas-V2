<?php

namespace App\Services;

use App\Models\Discount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class RecentlyViewedService
{
    public function create(Model $viewable)
    {
        session()->put(
            key: 'recently_viewed.'.$viewable::class,
            value: collect(session('recently_viewed.'.$viewable::class))
                ->prepend($viewable->getKey())
                ->unique()
                ->diff($this->filterViewables($viewable::class))
                ->take(10)
                ->all()
        );
    }

    public function get(string $viewable)
    {
        session()->put(
            key: 'recently_viewed.'.$viewable,
            value: collect(session('recently_viewed.'.$viewable))
                ->diff($this->filterViewables($viewable))
                ->take(10)
                ->all()
        );

        return session('recently_viewed.'.$viewable);
    }

    public function filterViewables(string $model)
    {
        return match ($model) {
            Discount::class => Discount::query()->onlyTrashed()->orWhere('is_active', false)->pluck('id'),
            default => $model::query()->onlyTrashed()->pluck('id'),
        };
    }
}
