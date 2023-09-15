<?php

namespace App\Livewire\Resources\DealResource\Pages;

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
use Filament\Support\RawJs;
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
        $this->amount = \head($this->record->amount);
    }

    public function createOrder($data)
    {
        $subtotal = $this->quantity * $this->amount;
        $discount = (int) \round($subtotal * ($this->record->public_percentage / 100 / 100));
        $tax = 0;
        $total = $subtotal - $discount;
        $data['xAmount'] = $total / 100;
        $data['xExp'] = $data['xExp_month'].$data['xExp_year'];

        if (boolval($data['use_new']) || empty(\data_get($data, 'xToken'))) {
            \data_forget($data, 'xToken');
        } else {
            \data_forget($data, 'xExp');
            \data_forget($data, 'xCardNum');
            \data_forget($data, 'xCVV');
        }
        \data_forget($data, 'use_new');

        // TODO: add email to the orders table or pass a user_id when creating the order.
        $order = Order::query()
            ->create([
                'user_id' => auth()->id(),
                'order_status' => OrderStatus::Pending,
                'payment_status' => PaymentStatus::Pending,
                'payment_method' => 'card',
                'order_date' => now(),
                'order_tax' => 0,
                'order_subtotal' => $subtotal,
                'order_discount' => $discount,
                'order_total' => $total,
            ]);

        $order->orderDetails()->create([
            'discount_id' => $this->record->getKey(),
            'quantity' => $this->quantity,
            'amount' => $this->amount,
            'public_percentage' => $this->record->public_percentage,
            'percentage' => $this->record->percentage,
        ]);

        $data['xInvoice'] = $order->order_column;

        $response = Http::post('https://x1.cardknox.com/gatewayjson', new CardknoxBody($data));

        if (filled($response->json('xResult')) && $response->json('xStatus') === 'Error') {
            $order->update([
                'order_status' => OrderStatus::Failed,
                'payment_status' => PaymentStatus::Failed,
            ]);

            Notification::make()->danger()
                ->title('Error')
                ->body($response->json('xError'))
                ->send();

            return;
        }

        $paymentIds = auth()->user()->cardknox_payment_method_ids ?? [];
        if (\array_key_exists('should_save_payment_detail', $data)) {
            $response = (new CreatePaymentMethod(
                customerId: auth()->user()->cardknox_customer_id,
                token: $response->json('xToken'),
                tokenType: 'cc',
                exp: $response->json('xExp'),
            ))->send();

            auth()->user()->update(['cardknox_payment_method_ids' => [
                ...$paymentIds,
                'cc' => $response->json('PaymentMethodId'),
            ]]);
        }

        \data_forget($data, 'should_save_payment_detail');

        $order->update([
            'order_status' => OrderStatus::Processing,
            'payment_status' => $response->json('xStatus'),
        ]);

        auth()->user()->notify(new OrderApprovedNotification($order));

        //TODO: Send Notification
        Notification::make()
            ->title('Order placed')
            ->success()
            ->send();

        return redirect()->route('orders.show', ['id' => $order->uuid]);
    }

    public function addToCart() {
        $this->validate();
        cart()->add($this->record?->getKey(), $this->quantity, $this->amount);

        $this->updateClicks();

        $this->dispatch('cart-item-added', ...['record' => [
            'name' => $this->record->name,
            'amount' => \Filament\Support\format_money($this->amount / 100, 'USD'),
            'quantity' => $this->quantity,
            'image_url' => $this->record->brand->getFirstMediaUrl('logo'),
        ]]);
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
