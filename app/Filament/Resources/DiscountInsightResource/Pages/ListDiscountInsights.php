<?php

namespace App\Filament\Resources\DiscountInsightResource\Pages;

use App\Filament\Resources\DiscountInsightResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDiscountInsights extends ListRecords
{
    protected static string $resource = DiscountInsightResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            DiscountInsightResource\Widgets\DiscountInsightWidget::class,
        ];
    }
}
