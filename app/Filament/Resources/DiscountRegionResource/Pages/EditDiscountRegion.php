<?php

namespace App\Filament\Resources\DiscountRegionResource\Pages;

use App\Filament\Resources\DiscountRegionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDiscountRegion extends EditRecord
{
    protected static string $resource = DiscountRegionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
