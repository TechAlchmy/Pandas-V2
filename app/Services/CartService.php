<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Discount;

class CartService
{
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
        session()->put(
            'cart_items',
            collect(session('cart_items'))
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
            ]);
    }

    protected function persist()
    {
        if (auth()->check()) {
            $cart = Cart::query()
                ->firstOrCreate([
                    'user_id' => auth()->id(),
                    'order_id' => null,
                ]);

            $cart->update(['items' => session('cart_items')]);
        }
    }
}
