@props(['records', 'title' => null])

@if ($records->isNotEmpty())
    <section x-data="{ shown: false }" x-intersect.once="shown = true" class="px-[min(6.99vw,50px)] py-8 bg-neutral-200 max-w-[1920px] mx-auto">
        <h3 x-show="shown" x-transition.opacity.duration.1000ms class="text-4xl">{{ $title }}</h3>
        <x-hr />
        <div x-show="shown" x-transition.opacity.duration.1000ms.delay.250ms class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($records as $record)
                <x-deal-card :record="$record" />
            @endforeach
        </div>
    </section>
@endif
