<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Cart;
use App\Models\Discount;
use App\Models\Order;
use Illuminate\Support\Str;

class CartService
{
    protected $cachedItems;

    public function add($id, $quantity, $amount)
    {
        return collect(session('cart_items'))
            ->where('id', $id)
            ->where('amount', $amount)
            ->whenEmpty(function () use ($id, $amount) {
                $result = $this->update(
                    key: $key = (string) Str::orderedUuid(),
                    id: $id,
                    quantity: 0,
                    amount: $amount,
                );

                return collect([$key => $result]);
            })
            ->map(function ($item, $key) use ($id, $quantity, $amount) {
                return $this->update($key, $id, session('cart_items.'.$key.'.quantity', 0) + $quantity, $amount);
            })
            ->firstWhere('id', $id);
    }

    public function update($key, $id, $quantity, $amount)
    {
        session()->put('cart_items.'.$key, [
            'id' => $id,
            'quantity' => $quantity,
            'amount' => $amount,
        ]);

        $this->persist();

        return session('cart_items.'.$key);
    }

    public function remove($key)
    {
        $item = session('cart_items.'.$key);
        session()->forget('cart_items.'.$key);

        $this->persist();

        return $item;
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

        $inactiveDiscounts = Discount::query()
            ->onlyTrashed()
            ->orWhere(fn ($query) => $query->inactive())
            ->orWhereHas('brand', function ($query) {
                $query->onlyTrashed()
                    ->orWhere('is_active', false);
            })
            ->pluck('id');

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
                ->reject(function ($item) use ($inactiveDiscounts) {
                    return $inactiveDiscounts->contains(data_get($item, 'id'));
                })
                ->all()
        );

        $this->persist();

        $records = Discount::query()->with('brand')->find(
            collect(session('cart_items'))->pluck('id')
        );

        return collect(session('cart_items'))
            ->map(function ($item) use ($records) {
                $record = $records->find($item['id']);
                $subtotal = $item['quantity'] * $item['amount'];
                $discount = $subtotal * ($record->public_percentage / 100);
                $itemTotal = $subtotal - $discount;
                return [
                    'itemable' => $record,
                    'quantity' => $item['quantity'],
                    'amount' => $item['amount'],
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'item_total' => $itemTotal,
                ];
            })
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
                return $carry + $item['subtotal'];
            }, 0);
    }

    public function tax()
    {
        return 0;
    }

    public function discount()
    {
        return $this->items()
            ->reduce(function ($carry, $item) {
                return $carry + $item['discount'];
            }, 0);
    }

    public function total()
    {
        return $this->subtotal()
            - $this->discount()
            + $this->tax();
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
