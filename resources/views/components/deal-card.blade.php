@props(['record'])

<div class="space-y-2 mt-4 min-h-full flex flex-col">

    <div class="flex-grow flex">
        <div class="flex-1">

            @if ($record->brand->hasMedia('logo'))
            <x-a class="inline-block h-16 overflow-hidden" :href="route('deals.show', ['id' => $record->slug])">
                {{ $record->brand->getFirstMedia('logo')->img()->attributes(['class' => 'object-contain max-w-[6rem] w-full h-full']) }}
            </x-a>
            @endif
        </div>

        <div class="flex-shrink-0">
            <x-a class="inline" :href="route('deals.show', ['id' => $record->slug])">
                <h4 class="text-sm md:text-xl font-light">{{ $record->brand->name }}</h4>
            </x-a>
            <p class="text-sm">{{ $record->name }}</p>
        </div>
    </div>

    <div class="h-20">
        <p class=" line-clamp-3">{{ $record->excerpt }}</p>
    </div>
    <div class="flex-grow"></div>

    <div>
        <x-link class="transition-transform duration-300 transform hover:scale-105 hover:bg-panda-green hover:border-transparent" outlined :href="route('deals.show', ['id' => $record->slug])">
            See Deals
        </x-link>
    </div>

    <div class="flex-grow"></div>
    <div class="flex-grow"></div>

</div>