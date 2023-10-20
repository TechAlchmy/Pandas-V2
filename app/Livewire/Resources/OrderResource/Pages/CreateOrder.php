<?php

namespace App\Livewire\Resources\OrderResource\Pages;

use App\Enums\DiscountVoucherTypeEnum;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Cart;
use App\Http\Integrations\Cardknox\Requests\CreatePaymentMethod;
use App\Models\Discount;
use App\Models\Order;
use App\Notifications\OrderApprovedNotification;
use App\Services\BlackHawkService;
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
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateOrder extends Component implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;
    use WithRateLimiting;

    public $data;

    public function mount()
    {
        $this->form->fill();
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
        $item = cart()->items()->get($id);

        if ($item['itemable']->voucher_type == DiscountVoucherTypeEnum::DefinedAmountsGiftCard) {
            if ($item['itemable']->limit_qty >= $quantity) {
                cart()->update($id, $item['itemable']->getKey(), $quantity, $amount);
                return;
            }

            Notification::make()
                ->title('Quantity limit reached ' . $item['itemable']->name)
                ->danger()
                ->send();
        }

        if ($item['itemable']->voucher_type == DiscountVoucherTypeEnum::TopUpGiftCard) {
            $amount *= 100;
            $amount = (int) $amount;
            if ($item['itemable']->bh_min <= $amount && $item['itemable']->bh_max >= $amount) {
                cart()->update($id, $item['itemable']->getKey(), $quantity, $amount);
                return;
            }

            Notification::make()
                ->title('limit is ' . \Filament\Support\format_money($item['itemable']->bh_min / 100, 'USD') . ' and ' . \Filament\Support\format_money($item['itemable']->bh_max / 100, 'USD'))
                ->danger()
                ->send();
        }
    }

    public function createOrder($data)
    {
        foreach (cart()->items() as $item) {
            if ($item['itemable']->voucher_type == DiscountVoucherTypeEnum::DefinedAmountsGiftCard) {
                if ($item['itemable']->limit_qty && $item['quantity'] > $item['itemable']->limit_qty) {
                    Notification::make()
                        ->danger()
                        ->title('Quantity maximum limit is ' . $item['itemable']->limit_qty)
                        ->send();
                    return;
                }

                if ($item['itemable']->limit_amount && $item['item_total'] > $item['itemable']->limit_amount) {
                    Notification::make()
                        ->danger()
                        ->title('Maximum amount allowed is ' . \Filament\Support\format_money($item['itemable']->limit_amount / 100, 'USD'))
                        ->send();
                    return;
                }
            }
            if ($item['itemable']->voucher_type == DiscountVoucherTypeEnum::TopUpGiftCard) {
                if ($item['itemable']->bh_min > $item['amount'] || $item['itemable']->bh_max < $item['amount']) {
                    Notification::make()
                        ->danger()
                        ->title('limit is ' . \Filament\Support\format_money($item['itemable']->bh_min / 100, 'USD') . ' and ' . \Filament\Support\format_money($item['itemable']->bh_max / 100, 'USD'))
                        ->send();
                    return;
                }
            }
        }

        $data['xAmount'] = cart()->total() / 100;
        $data['xExp'] = $data['xExp_month'] . $data['xExp_year'];

        try {
            DB::beginTransaction();
            // TODO: add email to the orders table or pass a user_id when creating the order.
            $order = cart()->createOrder();

            $data['xInvoice'] = $order->order_column;

            if (boolval($data['use_new']) || empty(\data_get($data, 'xToken'))) {
                \data_forget($data, 'xToken');
            } else {
                \data_forget($data, 'xExp');
                \data_forget($data, 'xCardNum');
                \data_forget($data, 'xCVV');
            }
            \data_forget($data, 'use_new');

            $cardknoxPayment = new CardknoxPayment;
            $response = $cardknoxPayment->charge(new CardknoxBody($data));

            if (filled($response->json('xResult')) && $response->json('xStatus') === 'Error') {
                throw new \Exception($response->json('xError'));
            }

            $paymentIds = auth()->user()->cardknox_payment_method_ids ?? [];
            if (\array_key_exists('should_save_payment_detail', $data)) {
                $paymentMethodResponse = (new CreatePaymentMethod(
                    customerId: auth()->user()->cardknox_customer_id,
                    token: $response->json('xToken'),
                    tokenType: 'cc',
                    exp: $response->json('xExp'),
                ))->send();

                auth()->user()->update(['cardknox_payment_method_ids' => [
                    ...$paymentIds,
                    'cc' => $paymentMethodResponse->json('PaymentMethodId'),
                ]]);
            }

            // $apiCall = BlackHawkService::order($order);
            // We no longer place order for blackhawk, but instead save it in our queue
            // TODO: if item quantity is 1 and if there's only one item, add it to queue with a flag that it's realtime

            $order->addToQueue();

            $order->update([
                'cardknox_refnum' => $response->json('xRefNum'),
                'order_status' => OrderStatus::Processing,
                'payment_status' => PaymentStatus::tryFrom((string) $response->json('xStatus')),
            ]);

            cart()->finalize($order);

            cart()->clear();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            Notification::make()
                ->title('Error')
                ->body($e->getMessage())
                ->send();

            return;
        }

        try {
            auth()->user()->notify(new OrderApprovedNotification($order));
        } catch (\Throwable $e) {
            logger()->error($e->getMessage());
        }

        //TODO: Send Notification
        Notification::make()
            ->title('Order placed')
            ->success()
            ->send();

        return redirect()->route('orders.show', ['id' => $order->uuid]);
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
        return view('livewire.resources.order-resource.pages.create-order');
    }
}
