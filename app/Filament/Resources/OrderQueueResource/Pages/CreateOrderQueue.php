<?php

namespace App\Filament\Resources\OrderQueueResource\Pages;

use App\Filament\Resources\OrderQueueResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrderQueue extends CreateRecord
{
    protected static string $resource = OrderQueueResource::class;
}
