<?php

namespace App\Livewire\Resources\DealResource\Pages;

use App\Models\Brand;
use App\Models\BrandOrganization;
use App\Models\Category;
use App\Models\Discount;
use App\Models\DiscountInsight;
use App\Models\OfferType;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Database\Query\Expression;
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
        $this->js('$wire.recordSearch()');
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(4)
            ->statePath('filter')
            ->schema([
                Forms\Components\Select::make('brand_id')
                    ->live()
                    ->hiddenLabel()
                    ->placeholder('Find Brands')
                    ->options(Brand::query()->forOrganization(auth()->user()->organization)->pluck('name', 'id'))
                    ->extraAttributes(['class' => 'rounded-none ring-transparent list-deals'])
                    ->getOptionLabelUsing(fn ($value) => Brand::find($value)?->name)
                    ->searchable(),
                Forms\Components\Select::make('offer_type_id')
                    ->live()
                    ->hiddenLabel()
                    ->placeholder('Find Offer Type')
                    ->options(OfferType::query()->forOrganization(auth()->user()->organization)->pluck('type', 'id'))
                    ->extraAttributes(['class' => 'rounded-none ring-transparent list-deals'])
                    ->getOptionLabelUsing(fn ($value) => OfferType::find($value)?->type)
                    ->searchable(),
                Forms\Components\Select::make('category_id')
                    ->live()
                    ->hiddenLabel()
                    ->placeholder('Find Category...')
                    ->options(Category::query()->where('is_active', true)->pluck('name', 'id'))
                    ->extraAttributes(['class' => 'rounded-none ring-transparent list-deals'])
                    ->getOptionLabelUsing(fn ($value) => Category::find($value)?->name)
                    ->searchable(),
                Forms\Components\TextInput::make('search')
                    ->extraAttributes(['class' => 'rounded-none ring-transparent', 'x-on:keyup.enter' => '$wire.resetPage()'])
                    ->hiddenLabel()
                    ->suffixIcon('magnifying-glass')
                    ->placeholder('Search Deals'),
            ]);
    }

    #[Computed()]
    public function deals()
    {
        return \App\Models\Discount::query()
            ->withBrand(auth()->user()?->organization)
            ->withVoucherType(auth()->user()?->organization)
            ->active()
            ->when($this->filter['search'], function ($query, $value) {
                $query->where(new Expression('lower(name)'), 'like', "%{$value}%")
                    ->orWhereRelation('tags', new Expression('lower(name)'), 'like', "%{$value}%")
                    ->orWhereHas('brand', function ($query) use ($value) {
                        $query->where(new Expression('lower(name)'), 'like', "%{$value}%")
                            ->orWhereRelation('categories', new Expression('lower(name)'), 'like', "%{$value}%");
                    });
            })
            ->when($this->filter['brand_id'], fn($query) => $query->where('brand_id', $this->filter['brand_id']))
            ->when($this->filter['category_id'], fn($query) => $query->whereRelation('brandCategories', 'category_id', $this->filter['category_id']))
            ->when($this->filter['offer_type_id'], fn($query) => $query->whereRelation('offerTypes', 'offer_types.id', $this->filter['offer_type_id']))
            ->when($this->sort, fn ($query, $value) => match ($value) {
                'created_at', 'percentage', 'views', 'clicks' => $query->orderByDesc($value),
                default => $query->inRandomOrder(),
            })
            ->paginate(12);
    }

    #[Computed()]
    public function featuredDeals()
    {
        return \App\Models\Discount::query()
            ->withBrand(auth()->user()?->organization)
            ->withOfferTypes(auth()->user()?->organization)
            ->withVoucherType(auth()->user()?->organization)
            ->active()
            ->whereHas('featuredDeals', function ($query) {
                $query->where('featured_deals.organization_id', auth()->user()?->organization?->getKey());
            })
            ->take(4)
            ->get()
            ->whenEmpty(function () {
                if ($this->hasActiveFilter) {
                    return null;
                }

                return \App\Models\Discount::query()
                    ->withBrand(auth()->user()?->organization)
                    ->where('is_active', true)
                    ->inRandomOrder()
                    ->take(4)
                    ->get();
            });
    }

    #[Computed]
    public function recentlyViewed()
    {
        return \App\Models\Discount::query()
            ->active()
            ->with('brand.media')
            ->take(4)
            ->find(recentlyViewed()->get(\App\Models\Discount::class));
    }

    #[Computed()]
    public function hasActiveFilter()
    {
        return collect($this->filter)
            ->contains(fn ($filter) => ! empty($filter));
    }

    public function recordSearch()
    {
        if (empty($this->filter['search'])) {
             return;
        }

        $insight = DiscountInsight::query()
            ->create([
                'term' => $this->filter['search'],
                'category_id' => $this->filter['category_id'],
                'brand_id' => $this->filter['brand_id'],
                'user_id' => auth()->id(),
                'page' => $this->getPage(),
            ]);

        if ($this->deals->isNotEmpty()) {
            $insight->discountInsightModels()
                ->createMany($this->deals->map(fn ($record) => [
                    'discount_id' => $record->getKey(),
                ])->all());
        }
    }

    public function updatedPage($page)
    {
        $this->recordSearch();
    }
}
