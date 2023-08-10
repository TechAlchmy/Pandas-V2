<?php

namespace App\Filament\Management\Resources\BrandOrganizationResource\Pages;

use App\Filament\Management\Resources\BrandOrganizationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBrandOrganization extends EditRecord
{
    protected static string $resource = BrandOrganizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
