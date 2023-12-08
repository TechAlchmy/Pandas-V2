<?php

namespace App\Filament\Resources\EnvVarResource\Pages;

use App\Filament\Resources\EnvVarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEnvVar extends EditRecord
{
    protected static string $resource = EnvVarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
