<?php

namespace App\Livewire\Pages;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Discount;
use App\Models\Order;
use App\Services\CardknoxPayment\CardknoxBody;
use App\Services\CardknoxPayment\CardknoxPayment;
use Filament\Forms;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Support\RawJs;
use Illuminate\Support\Carbon;

class Checkout extends Component implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;
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
                           ->maxLength(3)
                           ->required(),
                   ]),
            ]);
    }

    public function updateItem($id, $quantity, $amount)
    {
        $record = Discount::find($id);
        if ($record->limit_qty >= $quantity) {
            cart()->update($id, $quantity, $amount);
            return;
        }

        Notification::make()
            ->title('Quantity limit reached ' . $record->name)
            ->danger()
            ->send();
    }

    public function checkoutAction()
    {
        return Action::make('checkout')
            ->view('components.button', ['slot' => 'Proceed to checkout'])
            ->fillForm([
                'xEmail' => auth()->user()?->email,
            ])
            ->form([
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\TextInput::make('xEmail')
                           ->email()
                           ->view('forms.components.text-input')
                           ->hiddenLabel()
                           ->placeholder('Email')
                           ->default(auth()->user()?->email)
                           ->required(),
                        Forms\Components\TextInput::make('xCardNum')
                            ->mask(RawJs::make('$input.startsWith(\'34\') || $input.startsWith(\'37\')? \'9999 999999 99999\' : \'9999 9999 9999 9999\''))
                            ->view('forms.components.text-input')
                            ->hiddenLabel()
                            ->placeholder('Card number')
                            ->maxLength(16)
                            ->required(),
                        Forms\Components\Grid::make(2)
                            ->columnSpan(1)
                            ->columns(['default' => 2])
                            ->schema([
                                Forms\Components\TextInput::make('xExp_month')
                                    ->view('forms.components.text-input')
                                    ->extraInputAttributes(['class' => 'w-full', 'x-bind:placeholder' => 12])
                                    ->hiddenLabel()
                                    ->maxLength(2)
                                    ->minLength(2)
                                    ->placeholder('month')
                                    ->required(),
                                Forms\Components\TextInput::make('xExp_year')
                                    ->view('forms.components.text-input')
                                    ->extraInputAttributes(['class' => 'w-full', 'x-bind:placeholder' => str(date('Y'))->substr(2, 2)])
                                    ->hiddenLabel()
                                    ->maxLength(2)
                                    ->minLength(2)
                                    ->placeholder('year')
                                    ->required(),
                            ]),
                        Forms\Components\TextInput::make('xCVV')
                            ->view('forms.components.text-input')
                            ->extraInputAttributes(['x-bind:placeholder' => 123])
                            ->hiddenLabel()
                            ->placeholder('CVC')
                            ->minLength(3)
                            ->maxLength(3)
                            ->required(),
                    ]),
            ])
            ->action(function ($data) {
                $data['xAmount'] = 23;
                $data['xExp'] = $data['xExp_month'].$data['xExp_year'];

                // TODO: add email to the orders table or pass a user_id when creating the order.
                $order = cart()->createOrder();

                $data['xInvoice'] = $order->order_column;

                $cardknoxPayment = new CardknoxPayment;
                $response = $cardknoxPayment->charge(new CardknoxBody($data));

                if (filled($response->xResult) && $response->xStatus === 'Error') {
                    Notification::make()->danger()
                        ->title('Error')
                        ->body($response->xError)
                        ->send();

                    return;
                }

                $order->update([
                    'order_status' => OrderStatus::Processing,
                    'payment_status' => $response->xStatus,
                ]);

                //TODO: Send Notification
                Notification::make()
                    ->title('Order placed')
                    ->success()
                    ->send();

                return redirect()->route('dashboard', ['activeTab' => 4]);
            });
    }

    public function saveItemAction()
    {
        return Action::make('saveItem')
            ->view('components.cart-item-button', ['slot' => 'Save for later'])
            ->requiresConfirmation()
            ->action(function ($arguments) {
                cart()->remove($arguments['id']);

                savedProduct()->add($arguments['id']);

                Notification::make()
                    ->title('Item saved')
                    ->success()
                    ->send();
            });
    }

    public function removeItemAction()
    {
        return Action::make('removeItem')
            ->view('components.cart-item-button', ['slot' => 'Remove'])
            ->requiresConfirmation()
            ->action(function ($arguments) {
                cart()->remove($arguments['id']);
                Notification::make()
                    ->title('Cart Item removed')
                    ->success()
                    ->send();
            });
    }

    public function render()
    {
        return view('livewire.pages.checkout');
    }
}
