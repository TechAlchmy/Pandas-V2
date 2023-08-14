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

<div class="grid grid-cols-1 lg:grid-cols-4 p-8" x-init="$wire.set('isDesktop', window.innerWidth >= 1280)" x-on:resize.window="
    $wire.set('isDesktop', window.innerWidth >= 1280)
">
    <ul class="divide-y text-xl">
        @foreach (['Daily Deals', 'My Benefits', 'My Details', 'My Preferences', 'My Orders'] as $menu)
            <li class="p-4">
                <div>
                    <button :class="{ 'font-bold': $wire.selected == {{ $loop->index }} }" x-on:click="$wire.set('selected', {{ $loop->index }})">{{ $menu }}</button>
                </div>
                @unless ($isDesktop)
                    @if ($selected == $loop->index)
                        <livewire:is :component="$this->componentName" wire:key="{{ $loop->index }}" />
                    @endif
                @endunless
            </li>
        @endforeach
    </ul>
    @if ($isDesktop)
        <div class="col-span-3">
            <livewire:is :component="$this->componentName" :wire:key="$this->componentName" />
        </div>
    @endif
</div>
