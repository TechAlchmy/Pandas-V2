<?php

namespace App\Livewire\Resources\ContactInquiryResource\Forms;

use App\Models\ContactInquiry;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\Component;

class ContactUsForm extends Component implements HasForms
{
    use InteractsWithForms;

    public function create()
    {
        $data = $this->form->getState();

        $data['user_id'] = auth()->id();

        ContactInquiry::query()->create($data);

        Notification::make()
            ->success()
            ->title('Received! We will contact you back soon.')
            ->send();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->required(),
                Forms\Components\Textarea::make('content')
                    ->required(),
            ]);
    }
}
