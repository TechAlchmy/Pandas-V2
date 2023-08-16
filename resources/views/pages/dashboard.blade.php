<?php
use function Laravel\Folio\{middleware, name};

middleware(['auth', 'verified']);
name('dashboard');
?>
<x-layouts.app>
    <x-banner-upsell />

    <div class="flex px-[min(6.99vw,50px)] max-w-[1920px] mx-auto py-8 justify-between">
        <h1 class="text-4xl lg:text-7xl">My Panda</h1>
        <p class="hidden lg:block">{{ auth()->user()->organization?->name }}</p>
    </div>
    <x-profile-tabs />
</x-layouts.app>
