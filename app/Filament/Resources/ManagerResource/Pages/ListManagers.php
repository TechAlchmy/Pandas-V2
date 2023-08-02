<?php

namespace App\Filament\Resources\ManagerResource\Pages;

use App\Filament\Resources\ManagerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListManagers extends ListRecords
{
    protected static string $resource = ManagerResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
