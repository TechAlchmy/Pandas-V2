<?php

namespace App\Filament\Management\Resources\UserResource\Pages;

use App\Filament\Management\Resources\UserResource;
use App\Models\OrganizationInvitation;
use App\Models\User;
use App\Notifications\OrganizationInvitationCreatedNotification;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification as NotificationsNotification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Notification;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
            Actions\Action::make('invite')
                ->model(OrganizationInvitation::class)
                ->form([
                    Forms\Components\Grid::make()
                        ->schema([
                            Forms\Components\TextInput::make('email')
                                ->required()
                                ->email(),
                            Forms\Components\Toggle::make('is_manager')
                                ->required()
                                ->default(false),
                        ]),
                ])
                ->mutateFormDataUsing(function ($data) {
                    $data['organization_id'] = filament()->getTenant()->getKey();
                    return $data;
                })
                ->before(function ($action, $data) {
                    $user = User::query()
                        ->withTrashed()
                        ->firstWhere('email', $data['email']);

                    if (empty($user)) {
                        $alreadyExists = OrganizationInvitation::query()
                            ->where('organization_id', $data['organization_id'])
                            ->where('email', $data['email'])
                            ->exists();

                        if (! $alreadyExists) {
                            return;
                        }

                        NotificationsNotification::make()
                            ->warning()
                            ->title('This email has been invited already')
                            ->persistent()
                            ->send();

                        $action->halt();
                    }

                    if ($user->trashed()) {
                        NotificationsNotification::make()
                            ->warning()
                            ->title('This email is currently suspended!')
                            ->persistent()
                            ->send();

                        $action->halt();
                    }

                    if ($user->organization_id) {
                        NotificationsNotification::make()
                            ->warning()
                            ->title('This email cannot be invited!')
                            ->persistent()
                            ->send();

                        $action->halt();
                    }
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
