<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Cart;
use App\Models\Discount;
use App\Models\Order;

class CartService
{
    protected $cachedItems;

    public function add($id, $quantity, $amount)
    {
        $this->update($id, session('cart_items.'.$id.'.quantity', 0) + $quantity, $amount);
    }

    public function update($id, $quantity, $amount)
    {
        session()->put('cart_items.'.$id, ['quantity' => $quantity, 'amount' => $amount]);

        $this->persist();
    }

    public function remove($id)
    {
        session()->forget('cart_items.'.$id);

        $this->persist();
    }

    public function clear()
    {
        $items = $this->items();

        session()->put('cart_items', []);

        $this->persist();

        return $items;
    }

    public function items()
    {
        if ($this->cachedItems) {
            return $this->cachedItems;
        }

        session()->put(
            'cart_items',
            collect(session('cart_items'))
                ->when(auth()->check(), function () {
                    $cartItems = Cart::query()
                        ->where('user_id', auth()->id())
                        ->whereNull('order_id')
                        ->first(['items'])
                        ?->items;

                    return collect($cartItems);
                })
                ->forget(Discount::query()
                    ->onlyTrashed()
                    ->orWhere('is_active', false)
                    ->pluck('id'))
                ->all()
        );

        $this->persist();

        $records = Discount::query()->find(array_keys(session('cart_items')));

        return collect(session('cart_items'))
            ->map(fn ($item, $itemId) => [
                'itemable' => $records->find($itemId),
                'quantity' => $item['quantity'],
                'amount' => $item['amount'],
            ])
            ->when(empty($this->cachedItems), function ($items) {
                if ($items->isNotEmpty()) {
                    $this->cachedItems = $items;
                }
                return $items;
            });
    }

    public function subtotal()
    {
        return collect($this->items())
            ->reduce(function ($carry, $item) {
                return $carry + ($item['amount'] * $item['quantity']);
            }, 0);
    }

    public function tax()
    {
        return 0;
    }

    public function discount()
    {
        return 0;
    }

    public function total()
    {
        return $this->tax()
            + $this->subtotal();
    }

    public function createOrder()
    {
        $order = Order::query()
            ->create([
                'user_id' => auth()->id(),
                'order_status' => OrderStatus::Pending,
                'payment_status' => PaymentStatus::Pending,
                'payment_method' => 'card',
                'order_date' => now(),
                'order_tax' => $this->tax(),
                'order_subtotal' => $this->subtotal(),
                'order_discount' => $this->discount(),
                'order_total' => $this->total(),
            ]);

        foreach (cart()->items() as $id => $item) {
            $order->orderDetails()->create([
                'discount_id' => $id,
                'amount' => $item['amount'],
                'quantity' => $item['quantity'],
            ]);
        }

        if (auth()->check()) {
            Cart::query()
                ->whereBelongsTo(auth()->user())
                ->whereNull('order_id')
                ->update([
                    'order_id' => $order->getKey(),
                ]);
        }

        return $order;
    }

    protected function persist()
    {
        if (auth()->check()) {
            $cart = Cart::query()
                ->updateOrCreate([
                    'user_id' => auth()->id(),
                    'order_id' => null,
                ], [
                    'items' => session('cart_items'),
                ]);
        }
    }
}
