<?php

namespace App\Filament\Resources\DiscountCategoryResource\Pages;

use App\Filament\Resources\DiscountCategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDiscountCategory extends EditRecord
{
    protected static string $resource = DiscountCategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
