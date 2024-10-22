<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\LineChartWidget;
use Flowframe\Trend\Trend;

class MonthlyUsersChart extends ChartWidget
{
    protected static ?string $heading = 'Users';

    protected function getData(): array
    {
        $data = Trend::model(User::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();
        return [
            'datasets' => [
                [
                    'label' => 'Users',
                    'data' => $data->map(fn ($value) => $value->aggregate),
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
