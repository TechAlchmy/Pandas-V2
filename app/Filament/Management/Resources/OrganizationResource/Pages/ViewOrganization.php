<?php

namespace App\Filament\Management\Resources\OrganizationResource\Pages;

use App\Filament\Management\Resources\OrganizationResource;
use Filament\Actions;
use Filament\Notifications\Notification;
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
            Actions\Action::make('copy_registration_link')
                ->action(function ($record) {
                    $this->js('navigator.clipboard.writeText("'.$record->registration_link.'");');
                    Notification::make()
                        ->title('Registration link copied')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
