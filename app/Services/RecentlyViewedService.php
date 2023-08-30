<?php

namespace App\Services;

use App\Models\Discount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Carbon;

class RecentlyViewedService
{
    public function create(Model $viewable)
    {
        $model = $viewable::class;
        session()->put(
            key: 'recently_viewed.'.$model,
            value: collect(session('recently_viewed.'.$model))
                ->prepend($viewable->getKey())
                ->unique()
                ->diff($this->filterViewables($model))
                ->take(10)
                ->all()
        );

        $this->syncToDatabase($model);
    }

    public function get(string $model)
    {
        session()->put(
            key: 'recently_viewed.'.$model,
            value: collect(session('recently_viewed.'.$model))
                ->when(auth()->check(), function ($views) use ($model) {
                    return $views->concat(auth()->user()->recentViews()
                        ->where('viewable', $this->getViewable($model))
                        ->first(['views'])
                        ?->views ?? []);
                })
                ->unique()
                ->diff($this->filterViewables($model))
                ->take(10)
                ->all()
        );

        $this->syncToDatabase($model);

        return session('recently_viewed.'.$model);
    }

    protected function filterViewables(string $model)
    {
        return match ($model) {
            Discount::class => Discount::query()
                ->onlyTrashed()
                ->orWhere(fn ($query) => $query->inactive())
                ->orWhereHas('brand', function ($query) {
                    $query->onlyTrashed()
                        ->orWhere('is_active', false);
                })
                ->pluck('id'),
            default => $model::query()->onlyTrashed()->pluck('id'),
        };
    }

    protected function syncToDatabase(string $model)
    {
        if (auth()->check()) {
            auth()->user()->recentViews()->updateOrCreate([
                'viewable' => $this->getViewable($model),
            ], [
                'views' => session('recently_viewed.'.$model),
            ]);
        }
    }

    protected function getViewable(string $model): int|string|null
    {
        return collect(Relation::morphMap())->first(fn ($morph) => $morph == $model);
    }
}
