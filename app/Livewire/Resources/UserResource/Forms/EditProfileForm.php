<?php

namespace App\Livewire\Resources\UserResource\Forms;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\Component;

class EditProfileForm extends Component implements HasForms
{
    use InteractsWithForms;

    public $data;

    public function mount()
    {
        $this->form->fill(auth()->user()->toArray());
    }

    public function save()
    {
        $data = $this->form->getState();

        auth()->user()->update($data);

        Notification::make()
            ->success()
            ->title('Profile updated')
            ->send();
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns()
            ->statePath('data')
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('')
                    ->placeholder('Name')
                    ->view('forms.components.text-input')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('')
                    ->placeholder('Email')
                    ->view('forms.components.text-input')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone_number')
                    ->label('')
                    ->placeholder('Phone')
                    ->view('forms.components.text-input')
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->label('')
                    ->placeholder('Address')
                    ->view('forms.components.text-input')
                    ->maxLength(255),
            ]);
    }
}
