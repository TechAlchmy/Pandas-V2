<?php

namespace App\Filament\Resources\VoucherTypeResource\Pages;

use App\Filament\Resources\VoucherTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVoucherType extends EditRecord
{
    protected static string $resource = VoucherTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
