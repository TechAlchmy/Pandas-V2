<?php

namespace App\Livewire\Resources\UserResource\Forms;

use App\Http\Integrations\Cardknox\Requests\DeletePaymentMethod;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Livewire\Component;

class EditPreferencesForm extends Component implements HasForms
{
    use InteractsWithForms;

    public $data;

    public function mount()
    {
        $this->form->fill(auth()->user()->userPreference->toArray());
    }

    public function save()
    {
        $data = $this->form->getState();

        auth()->user()->userPreference->update($data);

        Notification::make()
            ->title('Successfully saved preferences')
            ->success()
            ->send();
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(['default' => 2, 'md' => 4])
            ->statePath('data')
            ->schema([
                Forms\Components\Toggle::make('email_notification')
                    ->offColor(Color::hex('#000000'))
                    ->onColor(Color::hex('#91b88f'))
                    ->default(false),
                Forms\Components\Toggle::make('sms_notification')
                    ->offColor(Color::hex('#000000'))
                    ->onColor(Color::hex('#91b88f'))
                    ->default(false),
                Forms\Components\Toggle::make('push_notification')
                    ->offColor(Color::hex('#000000'))
                    ->onColor(Color::hex('#91b88f'))
                    ->default(false),
                Forms\Components\Toggle::make('email_marketing')
                    ->offColor(Color::hex('#000000'))
                    ->onColor(Color::hex('#91b88f'))
                    ->default(false),
                Forms\Components\Actions::make([
                    Forms\Components\Actions\Action::make('forget_my_cc_info')
                        ->visible(fn () => filled(\data_get(auth()->user()->cardknox_payment_method_ids, 'cc')))
                        ->requiresConfirmation()
                        ->successNotificationTitle('CC Info has been removed')
                        ->outlined()
                        ->action(function ($action) {
                            (new DeletePaymentMethod(
                                \data_get(auth()->user()->cardknox_payment_method_ids, 'cc'),
                            ))->send();

                            auth()->user()->update(['cardknox_payment_method_ids' => []]);

                            $action->success();
                        }),
                ]),
            ]);
    }
}
