<?php

namespace App\Filament\Resources\DiscountCategoryResource\Pages;

use App\Filament\Resources\DiscountCategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDiscountCategories extends ListRecords
{
    protected static string $resource = DiscountCategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
