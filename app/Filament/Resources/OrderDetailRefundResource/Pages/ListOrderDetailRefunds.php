<?php

namespace App\Filament\Resources\OrderDetailRefundResource\Pages;

use App\Filament\Resources\OrderDetailRefundResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrderDetailRefunds extends ListRecords
{
    protected static string $resource = OrderDetailRefundResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
