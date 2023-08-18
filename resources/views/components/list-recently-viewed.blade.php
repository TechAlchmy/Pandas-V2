@php
    $recentlyViewed = \App\Models\Discount::query()
        ->with('brand.media')
        ->find(recentlyViewed()->get(\App\Models\Discount::class));
@endphp

@if ($recentlyViewed->isNotEmpty())
    <section class="px-[min(6.99vw,50px)] py-8 bg-neutral-200 max-w-[1920px] mx-auto">
        <h3 class="text-4xl">Recently Viewed</h3>
        <x-hr />
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($recentlyViewed as $viewable)
                <x-deal-card :record="$viewable" />
            @endforeach
        </div>
    </section>
@endif
