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

    <div class="px-[min(6.99vw,50px)] py-8">
        <div class="max-w-[1920px] mx-auto">
            <div class="flex justify-between items-center">
                <h1 class="text-4xl lg:text-7xl font-editorial">My Panda</h1>
                <p class="hidden lg:block">{{ auth()->user()->organization?->name }}</p>
            </div>
        </div>
    </div>
    <x-profile-tabs />
</x-layouts.app>
