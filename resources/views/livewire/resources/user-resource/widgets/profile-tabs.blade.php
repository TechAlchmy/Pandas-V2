<?php

use function Livewire\Volt\{state, computed};

state(['selected' => 0, 'isDesktop' => false]);
$componentName = computed(function () {
    return match ($this->selected) {
        0 => 'resources.user-resource.widgets.list-daily-deals',
        1 => 'resources.user-resource.widgets.list-benefits',
        2 => 'resources.user-resource.forms.edit-profile-form',
        3 => 'resources.user-resource.forms.edit-preferences-form',
        4 => 'resources.user-resource.widgets.list-orders',
        default => null,
    };
});
?>

<section class="grid grid-cols-1 lg:grid-cols-4 py-8 px-[min(6.99vw,50px)]">
    <ul class="divide-y text-xl">
        @foreach (['Daily Deals', 'My Benefits', 'My Details', 'My Preferences', 'My Orders'] as $menu)
            <li class="p-4">
                <div>
                    <button :class="{ 'font-bold': $wire.selected == {{ $loop->index }} }" x-on:click="$wire.set('selected', {{ $loop->index }})">{{ $menu }}</button>
                </div>
                <div class="lg:hidden">
                    @if ($selected == $loop->index)
                        <livewire:is :component="$this->componentName" wire:key="{{ $loop->index }}" />
                    @endif
                </div>
            </li>
        @endforeach
    </ul>

    <div class="col-span-3 hidden lg:block">
        <livewire:is :component="$this->componentName" :wire:key="$this->componentName" />
    </div>
</section>
