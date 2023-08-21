<?php

namespace App\Livewire\Resources\DealResource\Forms;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms;
use Filament\Forms\Form;
use Livewire\Component;

class RedeemNowForm extends Component implements HasForms
{
    use InteractsWithForms;

    public $data;

    public function mount()
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->columns()
            ->schema([
                Forms\Components\TextInput::make('xCardNum')
                    ->columnSpanFull()
                    ->hiddenLabel()
                    ->placeholder('Card number')
                    ->view('forms.components.text-input')
                    ->required(),
                Forms\Components\TextInput::make('xExp')
                    ->hiddenLabel()
                    ->placeholder('Expiration')
                    ->view('forms.components.text-input')
                    ->required(),
                Forms\Components\TextInput::make('xCVV')
                    ->view('forms.components.text-input')
                    ->hiddenLabel()
                    ->placeholder('CVC')
                    ->numeric()
                    ->minLength(3)
                    ->maxLength(3)
                    ->required(),
            ]);
    }
    public function render()
    {
        return view('livewire.resources.deal-resource.forms.redeem-now-form');
    }
}
