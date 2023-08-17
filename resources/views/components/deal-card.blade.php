@props(['record'])

<div class="space-y-2">
    <x-a :href="route('deals.show', ['id' => $record->slug])">
        <h4 class="text-4xl">{{ $record->name }}</h4>
    </x-a>
    <p class="text-2xl">{{ $record->percentage }}%</p>
    <p>More info about deal here</p>
    <x-link outlined :href="route('deals.show', ['id' => $record->slug])">
        See Deals
    </x-link>
</div>
