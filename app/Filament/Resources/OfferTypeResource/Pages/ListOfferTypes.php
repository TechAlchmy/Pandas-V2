<?php

namespace App\Filament\Resources\OfferTypeResource\Pages;

use App\Filament\Resources\OfferTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOfferTypes extends ListRecords
{
    protected static string $resource = OfferTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
