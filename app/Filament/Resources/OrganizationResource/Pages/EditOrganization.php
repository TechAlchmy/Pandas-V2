<?php

namespace App\Filament\Resources\OrganizationResource\Pages;

use App\Filament\Resources\OrganizationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrganization extends EditRecord
{
    protected static string $resource = OrganizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('portal')
                ->url(fn ($record) => route('filament.management.pages.dashboard', ['tenant' => $record]))
                ->openUrlInNewTab(),
            Actions\DeleteAction::make(),
        ];
    }
}
