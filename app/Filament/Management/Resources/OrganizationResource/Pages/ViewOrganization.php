<?php

namespace App\Filament\Management\Resources\OrganizationResource\Pages;

use App\Filament\Management\Resources\OrganizationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;

class ViewOrganization extends ViewRecord
{
    protected static string $resource = OrganizationResource::class;

    public static function getLabel(): string
    {
        return 'My Company';
    }

    public static function getSlug(): string
    {
        return 'profile';
    }

    public static function canView(Model $tenant): bool
    {
        return true;
    }

    public function mount(int | string | null $record = null): void
    {
        $this->record = filament()->getTenant();
    }

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
