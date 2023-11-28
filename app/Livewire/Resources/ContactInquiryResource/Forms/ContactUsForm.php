<?php

namespace App\Livewire\Resources\ContactInquiryResource\Forms;

use App\Models\ContactInquiry;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ContactUsForm extends Component implements HasForms
{
    use InteractsWithForms;

    public $data;

    public function mount()
    {
        $this->form->fill();
    }

    public function create()
    {
        $data = $this->form->getState();

        $data['user_id'] = auth()->id();

        ContactInquiry::query()->create($data);

        Notification::make()
            ->success()
            ->title('Received! We will contact you back soon.')
            ->send();

        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->hiddenLabel()
                    ->placeholder('Name')
                    ->view('forms.components.text-input')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->hiddenLabel()
                    ->placeholder('Email')
                    ->view('forms.components.text-input')
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->hiddenLabel()
                    ->placeholder('Phone')
                    ->view('forms.components.text-input')
                    ->maxLength(255),
                Forms\Components\Textarea::make('content')
                    ->required()
                    ->hiddenLabel()
                    ->placeholder('Message')
                    ->view('forms.components.textarea')
                    ->rows(6)
                    ->maxLength(255),
            ]);
    }

    #[Computed]
    public function testimonials()
    {
        return [
            ['Test 1', 'This is good!'],
            ['Test 2', 'Panda has helped me!'],
            ['Test 3', 'This is the benefits that I have wanted'],
            ['Test 1', 'This is is awesome for you employees'],
        ];
    }
}
