<?php

namespace App\Livewire\Resources\UserResource\Forms;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
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
            ->columns()
            ->statePath('data')
            ->schema([
                Forms\Components\Toggle::make('email_notification')
                    ->offColor('danger')
                    ->onColor('success')
                    ->default(false),
                Forms\Components\Toggle::make('sms_notification')
                    ->offColor('danger')
                    ->onColor('success')
                    ->default(false),
                Forms\Components\Toggle::make('push_notification')
                    ->offColor('danger')
                    ->onColor('success')
                    ->default(false),
                Forms\Components\Toggle::make('email_marketing')
                    ->offColor('danger')
                    ->onColor('success')
                    ->default(false),
            ]);
    }
}
