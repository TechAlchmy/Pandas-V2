@props(['record'])
@if ($record)
    <div class="bg-black text-center text-white px-[min(6.99vw,50px)] py-8">
        <div class="max-w-[1920px] mx-auto">
            <div class="flex flex-col lg:flex-row justify-between items-center gap-6">
                <div class="flex flex-col md:flex-row items-center gap-6 mx-auto lg:mx-0">
                    @if ($record->brand?->hasMedia('logo'))
                        {{ $record->brand?->getFirstMedia('logo')->img()->attributes(['class' => 'invert max-h-20']) }}
                    @else
                        <p>{{ $record->brand->name }}</p>
                    @endif
                    <h2 class="text-4xl md:text-6xl font-light font-editorial">
                        {{ $record->name }}
                    </h2>
                </div>
                <div>
                    <x-link :href="route('deals.show', ['id' => $record->slug])" size="lg" outlined color="white">
                        See Deal
                    </x-link>
                </div>
            </div>
        </div>
    </div>
@endif
