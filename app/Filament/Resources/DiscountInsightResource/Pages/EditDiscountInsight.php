<?php

namespace App\Filament\Resources\DiscountInsightResource\Pages;

use App\Filament\Resources\DiscountInsightResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDiscountInsight extends EditRecord
{
    protected static string $resource = DiscountInsightResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
