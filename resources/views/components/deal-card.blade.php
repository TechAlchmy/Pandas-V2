@props(['record', 'recordClicks' => false])

<div class="space-y-2" x-data="{ recordClicks: @js($recordClicks) }" x-on:click="if (recordClicks) $dispatch('deal-clicked', {id: {{ $record->getKey() }}})">
    <div class="">
        @if ($record->brand->hasMedia('logo'))
            {{ $record->brand->getFirstMedia('logo')->img()->attributes(['class' => 'w-full']) }}
        @else
            <div class="bg-gray w-full h-8">
                No Image
            </div>
        @endif
    </div>
    <x-a :href="route('deals.show', ['id' => $record->slug])">
        <h4 class="text-4xl">{{ $record->name }}</h4>
    </x-a>
    <p class="text-2xl">{{ $record->percentage }}%</p>
    <p>More info about deal here</p>
    <x-link outlined :href="route('deals.show', ['id' => $record->slug])">
        See Deals
    </x-link>
</div>
