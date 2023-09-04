<?php

namespace App\Filament\Management\Resources\OrganizationOfferTypeResource\Pages;

use App\Filament\Management\Resources\OrganizationOfferTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrganizationOfferType extends EditRecord
{
    protected static string $resource = OrganizationOfferTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
