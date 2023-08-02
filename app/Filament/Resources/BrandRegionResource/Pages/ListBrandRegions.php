<?php

namespace App\Filament\Resources\BrandRegionResource\Pages;

use App\Filament\Resources\BrandRegionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBrandRegions extends ListRecords
{
    protected static string $resource = BrandRegionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
