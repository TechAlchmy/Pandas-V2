<?php

namespace App\Livewire\Resources\DealResource\Pages;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Rule;
use Livewire\Component;

class ViewDeal extends Component
{
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
                ->with('brand.media')
                ->forOrganization(auth()->user()?->organization_id)
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
                ->forOrganization(auth()->user()?->organization_id)
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
            ->with('brand.media')
            ->with('categories')
            ->forOrganization(auth()->user()?->organization_id)
            ->where('is_active', true)
            ->where('slug', $this->id)
            ->firstOrFail();
    }
}
