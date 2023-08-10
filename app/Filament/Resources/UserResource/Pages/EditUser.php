<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        if ($this->getRecord()->trashed()) {
            Notification::make()
                ->title('This user is suspended!')
                ->color('danger')
                ->persistent()
                ->danger()
                ->send();
        }
    }

    public function getSubheading(): ?string
    {
        return $this->getRecord()->trashed()
            ? 'Suspended'
            : null;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->modalHeading('Suspend User')
                ->successNotificationTitle('User Suspended')
                ->successRedirectUrl(false)
                ->label('Suspend'),
            Actions\RestoreAction::make(),
        ];
    }
}
