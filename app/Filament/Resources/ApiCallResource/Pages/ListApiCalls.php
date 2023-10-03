<?php

namespace App\Filament\Resources\ApiCallResource\Pages;

use App\Filament\Resources\ApiCallResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApiCalls extends ListRecords
{
    protected static string $resource = ApiCallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
