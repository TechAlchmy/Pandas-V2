<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\OrganizationInvitation;
use App\Models\User;
use App\Notifications\OrganizationInvitationCreatedNotification;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification as NotificationsNotification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Notification;
use Livewire\Attributes\On;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    #[On('sendInvitation')]
    public function sendInvitation($record)
    {
        if (! filament()->auth()->user()->is_admin_or_manager) {
            return;
        }

        $record = OrganizationInvitation::query()->find($record);

        Notification::sendNow(
            $record,
            new OrganizationInvitationCreatedNotification($record)
        );

        NotificationsNotification::make()
            ->title('Successfully resent invitation')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('invite')
                ->model(OrganizationInvitation::class)
                ->form([
                    Forms\Components\Grid::make()
                        ->schema([
                            Forms\Components\Select::make('organization_id')
                                ->searchable()
                                ->relationship('organization', 'name')
                                ->required(),
                            Forms\Components\TextInput::make('email')
                                ->required()
                                ->email(),
                            Forms\Components\Toggle::make('is_manager')
                                ->required()
                                ->default(false),
                        ]),
                ])
                ->before(function ($action, $data) {
                    $user = User::query()
                        ->withTrashed()
                        ->firstWhere('email', $data['email']);

                    if (empty($user)) {
                        $record = OrganizationInvitation::query()
                            ->where('organization_id', $data['organization_id'])
                            ->where('email', $data['email'])
                            ->first();

                        if (! $record) {
                            return;
                        }

                        NotificationsNotification::make()
                            ->warning()
                            ->title('This email has been invited already')
                            ->persistent()
                            ->actions([
                                Action::make('resend')
                                    ->button()
                                    ->visible(filament()->auth()->user()->is_admin_or_manager)
                                    ->dispatch('sendInvitation', [['record' => $record->getKey()]]),
                            ])
                            ->send();

                        $action->halt();
                    }

                    NotificationsNotification::make()
                        ->warning()
                        ->title('This email is already registered!')
                        ->body($user->trashed() ? 'and suspended' : null)
                        ->persistent()
                        ->actions([
                            Action::make('view')
                                ->button()
                                ->url(UserResource::getUrl('edit', ['record' => $user]))
                                ->openUrlInNewTab(),
                        ])
                        ->send();

                    $action->halt();
                })
                ->action(function ($data, $action) {
                    $record = OrganizationInvitation::query()
                        ->create($data);

                    Notification::sendNow(
                        $record,
                        new OrganizationInvitationCreatedNotification($record)
                    );

                    $action->success();
                })
                ->successNotificationTitle('User has been invited!'),
        ];
    }
}
