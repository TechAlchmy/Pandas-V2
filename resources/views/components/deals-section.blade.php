@props(['records', 'title' => null])

@if ($records->isNotEmpty())
    <section class="px-[min(6.99vw,50px)] py-8 bg-neutral-200 max-w-[1920px] mx-auto">
        <h3 class="text-4xl">{{ $title }}</h3>
        <x-hr />
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($records as $record)
                <x-deal-card :record="$record" />
            @endforeach
        </div>
    </section>
@endif
