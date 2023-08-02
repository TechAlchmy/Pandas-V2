<?php

namespace App\Filament\Resources\OfferTypeResource\Pages;

use App\Filament\Resources\OfferTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOfferType extends EditRecord
{
    protected static string $resource = OfferTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
