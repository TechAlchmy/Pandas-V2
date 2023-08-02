<?php

namespace App\Filament\Resources\DiscountRegionResource\Pages;

use App\Filament\Resources\DiscountRegionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDiscountRegions extends ListRecords
{
    protected static string $resource = DiscountRegionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
