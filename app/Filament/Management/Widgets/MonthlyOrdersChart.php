<?php

namespace App\Filament\Management\Widgets;

use App\Models\Order;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;

class MonthlyOrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Spent';

    protected function getData(): array
    {
        $data = Trend::query(Order::query()->whereIn('user_id', User::query()->select('id')
                ->whereBelongsTo(filament()->getTenant())
            ))
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->sum('order_total');
        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $data->map(fn ($value) => $value->aggregate / 100),
                ],
            ],
            'labels' => $data->map(fn ($value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
