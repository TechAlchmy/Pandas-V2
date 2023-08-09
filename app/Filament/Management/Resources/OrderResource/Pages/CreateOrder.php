<?php

namespace App\Filament\Management\Resources\OrderResource\Pages;

use App\Filament\Management\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
}
