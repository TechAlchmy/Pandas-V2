<?php

namespace App\Livewire\Resources\DealResource\Pages;

use App\Models\Brand;
use App\Models\BrandOrganization;
use App\Models\Category;
use App\Models\Discount;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms;
use Filament\Forms\Form;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ListDeals extends Component implements HasForms
{
    use InteractsWithForms;
    use WithPagination;

    public $filter;

    public $sort;

    public function mount()
    {
        $this->form->fill(request('filter'));
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->statePath('filter')
            ->schema([
                Forms\Components\Select::make('brand_id')
                    ->live()
                    ->label('')
                    ->placeholder('Find Brands')
                    ->extraAttributes(['class' => 'rounded-none ring-transparent border-[0.5px] border-black list-deals'])
                    ->getOptionLabelUsing(fn ($value) => Brand::find($value)?->name)
                    ->getSearchResultsUsing(fn ($search) => Brand::query()
                        ->where('name', 'like', "%{$search}%")
                        ->where('is_active', true)
                        ->forOrganization(auth()->user()?->organization_id)
                        ->take(7)
                        ->get()
                        ->mapWithKeys(fn ($record) => [
                            $record->getKey() => $record->name,
                        ]))
                    ->searchable(),
                Forms\Components\Select::make('category_id')
                    ->live()
                    ->label('')
                    ->placeholder('Find Category...')
                    ->extraAttributes(['class' => 'rounded-none ring-transparent border-[0.5px] border-black list-deals'])
                    ->getOptionLabelUsing(fn ($value) => Category::find($value)?->name)
                    ->getSearchResultsUsing(function ($search) {
                        return Category::query()
                            ->where('name', 'like', "%{$search}%")
                            ->take(7)
                            ->get()
                            ->mapWithKeys(fn ($record) => [
                                $record->getKey() => $record->name,
                            ]);
                    })
                    ->searchable(),
                Forms\Components\TextInput::make('search')
                    ->live(debounce: 500)
                    ->extraAttributes(['class' => 'rounded-none ring-transparent border border-black'])
                    ->label('')
                    ->placeholder('Search Deals'),
            ]);
    }

    #[Computed()]
    public function deals()
    {
        return \App\Models\Discount::query()
            ->with('brand.media')
            ->forOrganization(auth()->user()?->organization_id)
            ->where('is_active', true)
            ->when($this->filter['search'], fn($query) => $query->where('name', 'like', "%{$this->filter['search']}%"))
            ->when($this->filter['brand_id'], fn($query) => $query->where('discounts.brand_id', $this->filter['brand_id']))
            ->when($this->filter['category_id'], fn($query) => $query->joinRelationship('discountCategories')->where('category_id', $this->filter['category_id']))
            ->when($this->sort, fn ($query, $value) => match ($value) {
                'created_at', 'percentage', 'views', 'clicks' => $query->orderByDesc($value),
                default => $query->inRandomOrder(),
            })
            ->simplePaginate(12);
    }

    #[Computed()]
    public function featuredDeals()
    {
        return \App\Models\Discount::query()
            ->with('brand.media')
            ->forOrganization(auth()->user()?->organization_id)
            ->where('is_active', true)
            ->joinRelationship('featuredDeals')
            ->where(fn($query) => $query->where('featured_deals.organization_id', auth()->user()?->organization_id))
            ->groupBy('discounts.id')
            ->take(4)
            ->get()
            ->whenEmpty(function () {
                if ($this->hasActiveFilter()) {
                    return null;
                }

                return \App\Models\Discount::query()
                    ->with('brand.media')
                    ->forOrganization(auth()->user()?->organization_id)
                    ->where('is_active', true)
                    ->inRandomOrder()
                    ->take(4)
                    ->get();
            });
    }

    public function recentlyViewed()
    {
        return \App\Models\Discount::query()
            ->with('brand.media')
            ->find(recentlyViewed()->get(\App\Models\Discount::class));
    }

    #[Computed()]
    public function hasActiveFilter()
    {
        return collect($this->filter)
            ->contains(fn ($filter) => ! empty($filter));
    }
}
