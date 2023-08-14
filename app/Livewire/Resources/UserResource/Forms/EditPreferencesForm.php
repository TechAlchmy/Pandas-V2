<?php

namespace App\Livewire\Resources\UserResource\Forms;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms;
use Filament\Forms\Form;
use Livewire\Component;

class EditPreferencesForm extends Component implements HasForms
{
    use InteractsWithForms;

    public $data;

    public function mount()
    {
        $this->form->fill(auth()->user()->userPreference->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns()
            ->statePath('data')
            ->schema([
                Forms\Components\Toggle::make('email_notification')
                    ->default(false),
                Forms\Components\Toggle::make('sms_notification')
                    ->default(false),
                Forms\Components\Toggle::make('push_notification')
                    ->default(false),
                Forms\Components\Toggle::make('email_marketing')
                    ->default(false),
            ]);
    }
}
