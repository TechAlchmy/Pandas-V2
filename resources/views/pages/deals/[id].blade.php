<?php
use function Laravel\Folio\{name};

name('deals.show');
?>
@php
    $record = \App\Models\Discount::query()
        ->where('slug', $id)
        ->firstOrFail();
@endphp
<x-layouts.app>
    <div class="grid grid-cols-1 lg:grid-cols-2 px-[min(6.99vw,50px)] py-8 gap-6">
        <div class="bg-gray"></div>
        <div class="">
            <h1 class="text-4xl">
                {{ $record->name }}
            </h1>
            <div>{!! $record->description !!}</div>
            <div>
                <x-button outlined size="lg">
                    Redeem
                </x-button>
            </div>
        </div>
    </div>
</x-layouts.app>
