<?php

namespace App\Filament\Resources\DiscountTypeResource\Pages;

use App\Filament\Resources\DiscountTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDiscountType extends EditRecord
{
    protected static string $resource = DiscountTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
