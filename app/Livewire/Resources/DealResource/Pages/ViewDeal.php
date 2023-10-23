<?php

namespace App\Livewire\Resources\DealResource\Pages;

use App\Enums\DiscountVoucherTypeEnum;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Integrations\Cardknox\Requests\CreatePaymentMethod;
use App\Models\Discount;
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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;
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
        if ($this->record->voucher_type != DiscountVoucherTypeEnum::TopUpGiftCard) {
            $this->amount = \head($this->record->amount);
        }
    }

    public function validateOrder()
    {
        if (empty($this->amount)) {
            Notification::make()
                ->danger()
                ->title('Please fill an amount')
                ->send();
            return;
        }

        $amount = $this->record->voucher_type == DiscountVoucherTypeEnum::DefinedAmountsGiftCard
            ? $this->amount
            : $this->amount * 100;
        $amount = (int) $amount;

        $subtotal = $this->quantity * $amount;

        if ($this->record->voucher_type == DiscountVoucherTypeEnum::DefinedAmountsGiftCard) {
            if ($this->record->limit_qty && $this->quantity > $this->record->limit_qty) {
                Notification::make()
                    ->danger()
                    ->title('Quantity maximum limit is ' . $this->record->limit_qty)
                    ->send();

                return;
            }

            if ($this->record->limit_amount && $subtotal > $this->record->limit_amount) {
                Notification::make()
                    ->danger()
                    ->title('Maximum amount allowed is ' . $this->record->limit_amount)
                    ->send();

                return;
            }
        }

        if ($this->record->voucher_type == DiscountVoucherTypeEnum::TopUpGiftCard) {
            if ($this->record->bh_min > $amount || $this->record->bh_max < $amount) {
                Notification::make()
                    ->danger()
                    ->title('limit is ' . \Filament\Support\format_money($this->record->bh_min / 100, 'USD') . ' and ' . \Filament\Support\format_money($this->record->bh_max / 100, 'USD'))
                    ->send();

                return;
            }
        }

        $this->dispatch('open-modal', ['id' => 'cardknox']);
    }

    public function createOrder($data)
    {
        $amount = $this->record->voucher_type == DiscountVoucherTypeEnum::DefinedAmountsGiftCard
            ? $this->amount
            : $this->amount * 100;
        $amount = (int) $amount;

        $subtotal = $this->quantity * $amount;

        if ($this->record->voucher_type == DiscountVoucherTypeEnum::DefinedAmountsGiftCard) {
            if ($this->record->limit_qty && $this->quantity > $this->record->limit_qty) {
                Notification::make()
                    ->danger()
                    ->title('Quantity maximum limit is ' . $this->record->limit_qty)
                    ->send();

                return;
            }

            if ($this->record->limit_amount && $subtotal > $this->record->limit_amount) {
                Notification::make()
                    ->danger()
                    ->title('Maximum amount allowed is ' . $this->record->limit_amount)
                    ->send();

                return;
            }
        }

        if ($this->record->voucher_type == DiscountVoucherTypeEnum::TopUpGiftCard) {
            if ($this->record->bh_min > $amount || $this->record->bh_max < $amount) {
                Notification::make()
                    ->danger()
                    ->title('limit is ' . \Filament\Support\format_money($this->record->bh_min / 100, 'USD') . ' and ' . \Filament\Support\format_money($this->record->bh_max / 100, 'USD'))
                    ->send();

                return;
            }
        }

        $discount = (int) \round($subtotal * ($this->record->public_percentage / 100 / 100));
        $tax = 0;
        $total = $subtotal - $discount;
        $data['xAmount'] = $total / 100;
        $data['xExp'] = $data['xExp_month'] . $data['xExp_year'];

        if (boolval($data['use_new']) || empty(\data_get($data, 'xToken'))) {
            \data_forget($data, 'xToken');
        } else {
            \data_forget($data, 'xExp');
            \data_forget($data, 'xCardNum');
            \data_forget($data, 'xCVV');
        }
        \data_forget($data, 'use_new');

        $data['xInvoice'] = Str::orderedUuid();

        try {
            $response = Http::post('https://x1.cardknox.com/gatewayjson', new CardknoxBody($data));

            if (filled($response->json('xResult')) && $response->json('xStatus') === 'Error') {
                throw new \Exception($response->json('xError'));
            }
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Error')
                ->body($e->getMessage())
                ->send();

            return;
        }

        DB::beginTransaction();

        try {
            $order = Order::query()
                ->create([
                    'uuid' => $data['xInvoice'],
                    'user_id' => auth()->id(),
                    'order_status' => OrderStatus::Processing,
                    'payment_status' => PaymentStatus::tryFrom((string) $response->json('xStatus')),
                    'cardknox_refnum' => $response->json('xRefNum'),
                    'payment_method' => 'card',
                    'order_date' => now(),
                    'order_tax' => $tax,
                    'order_subtotal' => $subtotal,
                    'order_discount' => $discount,
                    'order_total' => $total,
                ]);

            $order->orderDetails()->create([
                'discount_id' => $this->record->getKey(),
                'quantity' => $this->quantity,
                'amount' => $amount,
                'public_percentage' => $this->record->public_percentage,
                'percentage' => $this->record->percentage,
            ]);

            $order->addToQueue();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            Notification::make()
                ->title('Error')
                ->body($e->getMessage())
                ->warning()
                ->send();

            return;
        }

        try {
            if (\array_key_exists('should_save_payment_detail', $data)) {
                $paymentMethodResponse = (new CreatePaymentMethod(
                    customerId: auth()->user()->cardknox_customer_id,
                    token: $response->json('xToken'),
                    tokenType: 'cc',
                    exp: $response->json('xExp'),
                ))->send()->throw();

                $paymentIds = auth()->user()->cardknox_payment_method_ids ?? [];

                auth()->user()->update(['cardknox_payment_method_ids' => [
                    ...$paymentIds,
                    'cc' => $paymentMethodResponse->json('PaymentMethodId'),
                ]]);
            }
        } catch (\Throwable $e) {
            logger()->error($e->getMessage());

            Notification::make()
                ->title('Warning')
                ->body($e->getMessage())
                ->warning()
                ->send();
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

    public function addToCart()
    {
        $this->validate();
        if (empty($this->amount)) {
            Notification::make()
                ->danger()
                ->title('Please fill an amount')
                ->send();
            return;
        }

        $amount = $this->record->voucher_type == DiscountVoucherTypeEnum::DefinedAmountsGiftCard
            ? $this->amount
            : $this->amount * 100;
        $amount = (int) $amount;

        if ($this->record->voucher_type == DiscountVoucherTypeEnum::TopUpGiftCard) {
            if ($this->record->bh_min > $amount || $this->record->bh_max < $amount) {
                Notification::make()
                    ->danger()
                    ->title('limit is ' . \Filament\Support\format_money($this->record->bh_min / 100, 'USD') . ' and ' . \Filament\Support\format_money($this->record->bh_max / 100, 'USD'))
                    ->send();
                return;
            }
        }

        if ($this->record->voucher_type == DiscountVoucherTypeEnum::TopUpGiftCard) {
            if (cart()->items()->contains(function ($item) use ($amount) {
                return $item['itemable']->getKey() == $this->record?->getKey()
                    && $item['amount'] == $amount;
            })) {
                Notification::make()
                    ->danger()
                    ->title('Item already in your bag')
                    ->send();

                return;
            }
        }

        cart()->add($this->record?->getKey(), $this->quantity, $amount);

        $this->updateClicks();

        $this->dispatch('cart-item-added', ...['record' => [
            'name' => $this->record->name,
            'amount' => \Filament\Support\format_money($amount / 100, 'USD'),
            'quantity' => $this->quantity,
            'image_url' => $this->record->brand->getFirstMediaUrl('logo'),
        ]]);
    }

    public function handleClick()
    {
        $this->updateClicks();
        if ($this->record->voucher_type == DiscountVoucherTypeEnum::ExternalLink) {
            return redirect($this->record->link);
        }
    }

    public function render()
    {
        return view('livewire.resources.deal-resource.pages.view-deal', [
            'related' => \App\Models\Discount::query()
                ->withBrand(auth()->user()?->organization)
                ->withVoucherType(auth()->user()?->organization)
                ->active()
                ->whereIn(
                    'brand_id',
                    \App\Models\BrandCategory::query()
                        ->select('brand_id')
                        ->whereIn('category_id', $this->record->brand->categories->pluck('id')),
                )
                ->inRandomOrder()
                ->take(4)
                ->get(),
            'popular' => \App\Models\Discount::query()
                ->with('brand.media')
                ->withBrand(auth()->user()?->organization)
                ->withVoucherType(auth()->user()?->organization)
                ->active()
                ->orderByDesc('views')
                ->take(4)
                ->get(),
        ]);
    }

    #[Computed()]
    public function record()
    {
        return \App\Models\Discount::query()
            ->withBrand(auth()->user()?->organization)
            ->withVoucherType(auth()->user()?->organization)
            ->withExists(['orderDetails AS is_purchased' => function ($query) {
                $query->whereIn('order_id', Order::query()
                    ->select('id')
                    ->whereBelongsTo(auth()->user()));
            }])
            ->where('is_active', true)
            ->where('slug', $this->id)
            ->firstOrFail();
    }

    #[Renderless]
    public function updateClicks()
    {
        Discount::query()
            ->where('slug', $this->id)
            ->increment('clicks');
    }

    #[Renderless]
    public function updateViews()
    {
        Discount::query()
            ->where('slug', $this->id)
            ->increment('views');
    }
}
