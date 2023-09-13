<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Http\Integrations\Cardknox\Requests\CreateCustomer;
use Filament\Actions;
use Filament\Forms\Form;
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

    public function form(Form $form): Form
    {
        return parent::form($form)
            ->disabled(fn ($livewire) => $livewire->getRecord()->trashed());
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
            Actions\Action::make('createCardknoxCustomer')
                ->requiresConfirmation()
                ->hidden(app()->isProduction())
                ->visible(fn ($record) => empty($record->cardknox_customer_id))
                ->action(function ($record) {
                    $user = $record;
                    $response = (new CreateCustomer(
                        firstName: $user->first_name,
                        lastName: $user->last_name,
                        companyName: $user->organization?->name ?? config('app.name'),
                        customerNumber: $user->uuid,
                    ))->send();

                    $user->update(['cardknox_customer_id' => $response->json('CustomerId')]);
                }),
        ];
    }
}
