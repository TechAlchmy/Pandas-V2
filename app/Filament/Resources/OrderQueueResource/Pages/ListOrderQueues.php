<?php

namespace App\Filament\Resources\OrderQueueResource\Pages;

use App\Filament\Resources\OrderQueueResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrderQueues extends ListRecords
{
    protected static string $resource = OrderQueueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
