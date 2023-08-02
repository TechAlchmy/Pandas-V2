<?php

namespace App\Filament\Resources\BrandCategoryResource\Pages;

use App\Filament\Resources\BrandCategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBrandCategory extends EditRecord
{
    protected static string $resource = BrandCategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
