<?php

namespace App\Filament\Resources\VoucherTypeResource\Pages;

use App\Filament\Resources\VoucherTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVoucherTypes extends ListRecords
{
    protected static string $resource = VoucherTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
