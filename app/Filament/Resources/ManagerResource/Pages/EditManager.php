<?php

namespace App\Filament\Resources\ManagerResource\Pages;

use App\Filament\Resources\ManagerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditManager extends EditRecord
{
    protected static string $resource = ManagerResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
