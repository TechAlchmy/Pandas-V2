<?php

namespace App\Filament\Management\Resources\BrandOrganizationResource\Pages;

use App\Filament\Management\Resources\BrandOrganizationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBrandOrganizations extends ListRecords
{
    protected static string $resource = BrandOrganizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
