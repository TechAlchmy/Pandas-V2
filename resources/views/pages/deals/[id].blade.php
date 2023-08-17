<?php
use function Laravel\Folio\{name};

name('deals.show');
?>
@php
    $record = \App\Models\Discount::query()
        ->with('categories')
        ->forOrganization(auth()->user()?->organization_id)
        ->where('is_active', true)
        ->where('slug', $id)
        ->firstOrFail();
    $related = \App\Models\Discount::query()
        ->forOrganization(auth()->user()?->organization_id)
        ->where('is_active', true)
        ->whereIn(
            'id',
            \App\Models\DiscountCategory::query()
                ->select('discount_id')
                ->whereIn('category_id', $record->categories->pluck('id')),
        )
        ->take(4)
        ->get();
    
    $popular = \App\Models\Discount::query()
        ->forOrganization(auth()->user()?->organization_id)
        ->where('is_active', true)
        ->orderByDesc('views')
        ->take(4)
        ->get();
@endphp
<x-layouts.app>
    <section class="px-[min(6.99vw,50px)] max-w-[1920px] mx-auto py-8">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-gray"></div>
            <div class="">
                <h1 class="text-4xl">
                    {{ $record->name }}
                </h1>
                <div>
                    <x-button outlined size="lg">
                        Redeem
                    </x-button>
                </div>
            </div>
        </div>
    </section>
    @if ($related->isNotEmpty())
        <section class='px-[min(6.99vw,50px)] py-8 max-w-[1920px] mx-auto'>
            <x-hr />
            <h3 class="text-4xl">Related Deals</h3>
            <div class="h-28"></div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($related as $deal)
                    <x-deal-card :record="$deal" :record-clicks="true" />
                @endforeach
            </div>
        </section>
    @endif
    @if ($popular->isNotEmpty())
        <section class='px-[min(6.99vw,50px)] py-8 max-w-[1920px] mx-auto'>
            <x-hr />
            <h3 class="text-4xl">Related Deals</h3>
            <div class="h-28"></div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($popular as $deal)
                    <x-deal-card :record="$deal" :record-clicks="true" />
                @endforeach
            </div>
        </section>
    @endif
    <livewire:resources.recently-viewed-resource.widgets.create-recently-viewed :viewable="$record" />
</x-layouts.app>
