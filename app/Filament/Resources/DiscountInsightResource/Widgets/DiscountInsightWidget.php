<?php

namespace App\Filament\Resources\DiscountInsightResource\Widgets;

use App\Filament\Widgets\StatsOverview;
use App\Models\DiscountInsight;
use App\Models\DiscountInsightModel;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DiscountInsightWidget extends StatsOverview
{
    protected function getStats(): array
    {
        $topSearchedTerms = DiscountInsight::query()
            ->selectRaw('count(*) as total, term')
            ->groupBy('term')
            ->orderByDesc('total')
            ->first();

        $topCommonDeal = DiscountInsightModel::query()
            ->with('discount.brand')
            ->selectRaw('count(*) as total, discount_id')
            ->groupBy('discount_id')
            ->orderByDesc('total')
            ->first();
        return [
            Stat::make('Top searched terms', $topSearchedTerms?->term)
                ->description($topSearchedTerms?->total),
            Stat::make('The Most Common Deal', $topCommonDeal?->discount?->name)
                ->description($topCommonDeal?->discount?->brand?->name . " ({$topCommonDeal?->total}x)"),
            // Stat::make('Average time on page', '3:12'),
        ];
    }
}
