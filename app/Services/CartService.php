<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Discount;

class CartService
{
    protected $cachedItems;

    public function add($id, $quantity = 1)
    {
        $this->update($id, session('cart_items.'.$id, 0) + $quantity);
    }

    public function update($id, $quantity)
    {
        session()->put('cart_items.'.$id, $quantity);

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
                    return Cart::query()
                        ->where('user_id', auth()->id())
                        ->whereNull('order_id')
                        ->first(['items'])
                        ?->items;
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
            ->map(fn ($quantity, $itemId) => [
                'itemable' => $records->find($itemId),
                'quantity' => $quantity,
            ])
            ->whenNotEmpty(function ($items) {
                $this->cachedItems = $items;
                return $items;
            });
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
