<?php
use function Laravel\Folio\{middleware, name};

middleware(['auth', 'verified']);
name('dashboard');
?>
@php
    $featuredDiscount = \App\Models\Discount::query()
        ->withBrand(auth()->user()?->organization)
        ->whereHas('featuredDeals')
        ->active()
        ->inRandomOrder()
        ->first();
@endphp
<x-layouts.app class="bg-neutral-100">
    <x-banner-upsell :record="$featuredDiscount" />

    <div class="flex px-[min(6.99vw,50px)] max-w-[1920px] mx-auto py-8 justify-between">
        <h1 class="text-4xl lg:text-7xl">My Panda</h1>
        <p class="hidden lg:block">{{ auth()->user()->organization?->name }}</p>
    </div>
    <x-profile-tabs />
</x-layouts.app>
