@php
    $recentlyViewed = \App\Models\Discount::query()->find(recentlyViewed()->get(\App\Models\Discount::class));
@endphp

@if ($recentlyViewed->isNotEmpty())
    <section class="px-[min(6.99vw,50px)] py-8">
        <h3 class="text-4xl">Recently Viewed</h3>
        <x-hr />
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-6">
            @foreach ($recentlyViewed as $viewable)
                <div class="space-y-2">
                    <x-a :href="route('deals.show', ['id' => $viewable->slug])">
                        <h4 class="text-4xl">{{ $viewable->name }}</h4>
                    </x-a>
                    <p class="text-2xl">{{ $viewable->percentage }}%</p>
                    <p>More info about deal here</p>
                    <x-link outlined :href="route('deals.show', ['id' => $viewable->slug])">
                        See Deals
                    </x-link>
                </div>
            @endforeach
        </div>
    </section>
@endif
