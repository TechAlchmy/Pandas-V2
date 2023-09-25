<?php

namespace App\Filament\Resources\OrderDetailRefundResource\Pages;

use App\Filament\Resources\OrderDetailRefundResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrderDetailRefund extends EditRecord
{
    protected static string $resource = OrderDetailRefundResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
