<?php

namespace App\Filament\Resources\OrderRefundResource\Pages;

use App\Filament\Resources\OrderRefundResource;
use App\Http\Integrations\Cardknox\Requests\CreateCcRefund;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrderRefund extends EditRecord
{
    protected static string $resource = OrderRefundResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('approve')
                ->visible(fn ($record) => empty($record->approved_at))
                ->action(function ($record, $action) {
                    $record->update([
                        'approved_at' => now(),
                        'approved_by_id' => filament()->auth()->id(),
                    ]);

                    (new CreateCcRefund($record->order->order_column, $record->actual_amount))
                        ->send();

                    $action->success();
                })
                ->successNotificationTitle('Refund Request approved'),
        ];
    }
}
