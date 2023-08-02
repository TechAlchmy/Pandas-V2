<?php

namespace App\Filament\Resources\DiscountTagResource\Pages;

use App\Filament\Resources\DiscountTagResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDiscountTag extends EditRecord
{
    protected static string $resource = DiscountTagResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
