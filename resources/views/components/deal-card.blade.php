@props(['record', 'recordClicks' => false])

<div class="space-y-2 min-h-full flex flex-col" x-data="{ recordClicks: @js($recordClicks) }" x-on:click="if (recordClicks) $dispatch('deal-clicked', {id: {{ $record->getKey() }}})">
    <div class="min-h-[4rem]">
        @if ($record->brand->hasMedia('logo'))
            <x-a class="inline-block" :href="route('deals.show', ['id' => $record->slug])">
                {{ $record->brand->getFirstMedia('logo')->img()->attributes(['class' => 'max-w-[6rem] w-full']) }}
            </x-a>
        @else
            <div class="bg-gray w-full h-8">
                No Image
            </div>
        @endif
    </div>
    <x-a class="inline-block" :href="route('deals.show', ['id' => $record->slug])">
        <h4 class="text-4xl font-light">{{ $record->brand->name }}</h4>
    </x-a>
    <p class="text-2xl">{{ $record->name }}</p>
    <p>More info about deal here</p>
    <div class="flex-grow"></div>
    <div>
        <x-link outlined :href="route('deals.show', ['id' => $record->slug])">
            See Deals
        </x-link>
    </div>
</div>
