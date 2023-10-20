@props(['record'])

<div class="space-y-2 mt-4 min-h-full flex flex-col">
    <div class="min-h-[4rem]">
        @if ($record->brand->hasMedia('logo'))
        <x-a class="inline-block h-16 overflow-hidden" :href="route('deals.show', ['id' => $record->slug])">
            {{ $record->brand->getFirstMedia('logo')->img()->attributes(['class' => 'object-contain max-w-[6rem] w-full h-full']) }}
        </x-a>
        @endif
    </div>
    <x-a class="inline-block" :href="route('deals.show', ['id' => $record->slug])">
        <h4 class="text-4xl font-light">{{ $record->brand->name }}</h4>
    </x-a>
    <p class="text-2xl">{{ $record->name }}</p>
    <p>{{ $record->excerpt }}</p>
    <!-- <div class="flex-grow"></div> -->

    <!-- <div>
        <x-link class="hover:bg-panda-green" outlined :href="route('deals.show', ['id' => $record->slug])">
            See Deals
        </x-link>
    </div> -->

    <div class="inline-block">
        <x-link class="bg-gradient-to-l from-panda-green to-panda-green bg-no-repeat bg-right transition-all duration-500 ease-in-out transform hover:scale-105 hover:bg-left hover:bg-panda-green" style="background-size: 200% 100%;" outlined :href="route('deals.show', ['id' => $record->slug])">
            See Deals
        </x-link>
    </div>

</div>