<?php

namespace App\Filament\Resources\EnvVarResource\Pages;

use App\Filament\Resources\EnvVarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEnvVars extends ListRecords
{
    protected static string $resource = EnvVarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
