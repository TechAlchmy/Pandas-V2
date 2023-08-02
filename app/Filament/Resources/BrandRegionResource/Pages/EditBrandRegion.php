<?php

namespace App\Filament\Resources\BrandRegionResource\Pages;

use App\Filament\Resources\BrandRegionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBrandRegion extends EditRecord
{
    protected static string $resource = BrandRegionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
