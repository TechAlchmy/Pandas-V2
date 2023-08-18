<?php

namespace App\Livewire\Pages;

use App\Models\Order;
use App\Services\CardknoxPayment\CardknoxBody;
use App\Services\CardknoxPayment\CardknoxPayment;
use Filament\Forms;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;

class Checkout extends Component implements HasForms
{
    use InteractsWithForms;
    use WithRateLimiting;

    public $data;

    public function mount()
    {
        $this->form->fill();
    }

    public function confirmOrder()
    {
        $data = $this->form->getState();
        $data['xAmount'] = 23;
        $data['xExp'] = Carbon::make($data['xExp'])->format('my');

        // TODO: add email to the orders table or pass a user_id when creating the order.
        $order = Order::create([
            'order_total' => $data['xAmount'],
            'order_number' => random_int(1, 9999),
        ]);

        $data['xInvoice'] = 'Order-' . $order->id;

        $cardknoxPayment = new CardknoxPayment;
        $response = $cardknoxPayment->charge(new CardknoxBody($data));

        if (filled($response->xResult) && $response->xStatus === 'Error') {
            Notification::make()->danger()
                ->title('Error')
                ->body($response->xError)
                ->send();

            return;
        }

        $order->update(['payment_status' => $response->xStatus]);

        $this->redirect(route('pages.order.summary', $order));
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->columns(2)
            ->schema([
                Forms\Components\Section::make('Contact information')
                   ->schema([
                       Forms\Components\TextInput::make('xEmail')
                           ->email()
                           ->label('Email')
                           ->required(),
                   ]),

                Forms\Components\Section::make('Shipping information')
                   ->columns(2)
                   ->schema([
                       Forms\Components\TextInput::make('xShipFirstname')
                           ->label('First name')
                           ->required(),
                       Forms\Components\TextInput::make('xShipLastname')
                           ->label('Last name')
                           ->required(),
                       Forms\Components\TextInput::make('xShipCompany')
                        ->columnSpanFull()
                           ->label('Company')
                           ->required(),
                       Forms\Components\TextInput::make('xShipStreet')
                        ->columnSpanFull()
                           ->label('Address')
                           ->required(),
                       Forms\Components\TextInput::make('xShipCity')
                           ->label('City')
                           ->required(),
                       Forms\Components\TextInput::make('xShipCountry')
                           ->label('Country')
                           ->required(),
                       Forms\Components\TextInput::make('xShipState')
                           ->label('State')
                           ->required(),
                       Forms\Components\TextInput::make('xShipZip')
                           ->label('Zip/Postal code')
                           ->required(),
                   ]),

                Forms\Components\Section::make('Payment')
                   ->columns(2)
                   ->schema([
                       Forms\Components\TextInput::make('xCardNum')
                        ->columnSpanFull()
                           ->label('Card number')
                           ->required(),
                       Forms\Components\DatePicker::make('xExp')
                            ->minDate(now())
                           ->label('Expiration date')
                           ->required(),
                       Forms\Components\TextInput::make('xCVV')
                           ->label('CVC')
                           ->numeric()
                           ->minLength(3)
                           ->required(),
                   ]),
            ]);
    }

    public function render()
    {
        return view('livewire.pages.checkout')
            ->layout('components.layouts.guest');
    }
}
