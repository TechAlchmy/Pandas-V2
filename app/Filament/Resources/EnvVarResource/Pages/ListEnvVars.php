<?php

namespace App\Filament\Resources\EnvVarResource\Pages;

use App\Filament\Resources\EnvVarResource;
use App\Mail\TestMail;
use Filament\Forms;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Mail;

class ListEnvVars extends ListRecords
{
    protected static string $resource = EnvVarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('test_mail')
                ->form([
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required(),
                ])
                ->action(function ($action, $data) {
                    try {
                        Mail::to($data['email'])->send(new TestMail);
                    } catch (\Throwable $e) {
                        logger()->error($e->getMessage());
                    }
                    $action->success();
                }),
        ];
    }
}
