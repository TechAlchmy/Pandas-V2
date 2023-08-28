<?php

namespace App\Livewire\Resources\DealResource\Pages;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Notifications\OrderApprovedNotification;
use App\Services\CardknoxPayment\CardknoxBody;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\RawJs;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Rule;
use Livewire\Component;

class ViewDeal extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    #[Locked]
    public $id;

    #[Rule(['required', 'min:1'])]
    public $quantity = 1;

    #[Rule(['required'])]
    public $amount;

    public function mount()
    {
        $this->amount = data_get($this->record->amount, '0');
    }

    public function redeemAction(): Action
    {
        return Action::make('redeem')
            ->view('components.button', ['slot' => 'Redeem now'])
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
                $data['xAmount'] = $this->amount;
                $data['xExp'] = $data['xExp_month'].$data['xExp_year'];

                // TODO: add email to the orders table or pass a user_id when creating the order.
                $order = Order::query()
                    ->create([
                        'user_id' => auth()->id(),
                        'order_status' => OrderStatus::Pending,
                        'payment_status' => PaymentStatus::Pending,
                        'payment_method' => 'card',
                        'order_date' => now(),
                        'order_tax' => 0,
                        'order_subtotal' => $this->amount,
                        'order_discount' => 0,
                        'order_total' => $this->amount,
                    ]);

                $order->orderDetails()->create([
                    'discount_id' => $this->record->getKey(),
                    'quantity' => $this->quantity,
                    'amount' => $this->amount,
                ]);

                $data['xInvoice'] = $order->order_column;

                $response = Http::post('https://x1.cardknox.com/gatewayjson', new CardknoxBody($data))
                    ->object();

                if (filled($response->xResult) && $response->xStatus === 'Error') {
                    $order->update([
                        'order_status' => OrderStatus::Failed,
                        'payment_status' => PaymentStatus::Failed,
                    ]);

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

                auth()->user()->notify(new OrderApprovedNotification($order));

                //TODO: Send Notification
                Notification::make()
                    ->title('Order placed')
                    ->success()
                    ->send();

                return redirect()->route('dashboard', ['activeTab' => 4]);
            });
    }

    public function addToCart() {
        $this->validate();
        cart()->add($this->record?->getKey(), $this->quantity, $this->amount);

        $this->dispatch('cart-item-added', ...['record' => [
            'name' => $this->record->name,
            'amount' => \Filament\Support\format_money($this->amount, 'USD'),
            'quantity' => $this->quantity,
            'image_url' => $this->record->brand->getFirstMediaUrl('logo'),
        ]]);
    }

    public function render()
    {
        return view('livewire.resources.deal-resource.pages.view-deal', [
            'related' => \App\Models\Discount::query()
                ->withWhereHas('brand', function ($query) {
                    $query->with('media')->where('is_active', true)->forOrganization(auth()->user()?->organization);
                })
                ->where('is_active', true)
                ->whereIn(
                    'id',
                    \App\Models\DiscountCategory::query()
                        ->select('discount_id')
                        ->whereIn('category_id', $this->record->categories->pluck('id')),
                )
                ->take(4)
                ->get(),
            'popular' => \App\Models\Discount::query()
                ->with('brand.media')
                ->withWhereHas('brand', function ($query) {
                    $query->with('media')->where('is_active', true)->forOrganization(auth()->user()?->organization);
                })
                ->where('is_active', true)
                ->orderByDesc('views')
                ->take(4)
                ->get(),
            'record' => $this->record,
        ]);
    }

    #[Computed()]
    public function record()
    {
        return \App\Models\Discount::query()
            ->withWhereHas('brand', function ($query) {
                $query->with('media')->where('is_active', true)->forOrganization(auth()->user()?->organization);
            })
            ->with('categories')
            ->withExists(['orderDetails AS is_purchased' => function ($query) {
                $query->whereIn('order_id', Order::query()
                    ->select('id')
                    ->whereBelongsTo(auth()->user()));
            }])
            ->where('is_active', true)
            ->where('slug', $this->id)
            ->firstOrFail();
    }
}
