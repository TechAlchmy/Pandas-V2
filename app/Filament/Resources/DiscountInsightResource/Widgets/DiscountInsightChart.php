<?php

namespace App\Filament\Resources\DiscountInsightResource\Widgets;

use Filament\Widgets\ChartWidget;

class DiscountInsightChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
