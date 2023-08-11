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
            ->model(auth()->user())
            ->columns()
            ->schema([
                Forms\Components\TextInput::make('name'),
                Forms\Components\TextInput::make('email'),
                Forms\Components\TextInput::make('phone'),
                Forms\Components\TextInput::make('address'),
            ]);
    }
}
