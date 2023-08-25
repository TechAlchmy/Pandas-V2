@php
    $record = \App\Models\Discount::query()
        ->with('brand.media')
        ->forOrganization(auth()->user()?->organization_id)
        ->whereHas('featuredDeals')
        ->first();
@endphp
@if ($record)
    <div class="bg-black text-center text-white px-[min(6.99vw,50px)] py-8 lg:flex lg:justify-between lg:items-center gap-6">
        <div class="flex items-center gap-6">
            @if ($record->brand?->hasMedia('logo'))
                {{ $record->brand?->getFirstMedia('logo')->img()->attributes(['class' => 'invert']) }}
            @else
                <p>{{ $record->name }}</p>
            @endif
            <h2 class="text-6xl font-light font-editorial">
                {{ $record->name }}
            </h2>
        </div>
        <div>
            <x-link :href="route('deals.show', ['id' => $record->slug])" size="lg" outlined color="white">
                Learn More
            </x-link>
        </div>
    </div>
@endif
