<?php

namespace App\Filament\Resources\OfferTypeResource\Pages;

use App\Filament\Resources\OfferTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOfferTypes extends ListRecords
{
    protected static string $resource = OfferTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
