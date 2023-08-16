<?php
use function Laravel\Folio\{middleware, name};

middleware(['auth', 'verified']);
name('dashboard');
?>
<x-layouts.app>
    <livewire:resources.user-resource.widgets.upsell-widget />

    <div class="flex px-[min(6.99vw,50px)] py-8 justify-between">
        <h1 class="text-4xl lg:text-7xl">My Panda</h1>
        <p class="hidden lg:block">{{ auth()->user()->organization?->name }}</p>
    </div>
    <livewire:resources.user-resource.widgets.profile-tabs />
</x-layouts.app>
