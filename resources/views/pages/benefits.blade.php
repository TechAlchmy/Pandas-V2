<?php
use function Laravel\Folio\{name};

name('benefits');
?>
@php
    $categories = \App\Models\Category::query()
        ->where('is_active', true)
        ->get();
@endphp
<x-layouts.app>
    <section class="">
        <div class="px-[min(6.99vw,50px)] py-8 max-w-[1920px] mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-data="{ shown: false }" x-intersect.once="shown = true">
                <h1 x-show="shown" x-transition.duration.1500 class="text-6xl max-w-xl mix-blend-difference text-panda-green">Get Ready to go beyond the basic</h1>
                <div class="space-y-6 lg:ml-56 xl:ml-80">
                    <p class="">
                        Real benefits for real people start with Panda. We’re here to help you thrive in ways that matters to you, so you can give more when it’s go time—in life and work.

                        Explore the benefits your employer is providing through Panda People.
                    </p>
                    <x-button class="hover:bg-panda-green" outlined :href="route('dashboard', ['activeTab' => 1])">View My Benefits</x-button>
                </div>
            </div>
        </div>
        <img class="min-w-[125vw] -ml-20 md:min-w-[100vw] -mt-16 lg:-mt-80 xl:-mt-96" src="{{ asset('storage/assets/benefit-circles.png') }}" alt="Collages images" />
    </section>
    <section class="px-[min(6.99vw,50px)] py-8 max-w-[1920px] mx-auto md:-mt-40">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div x-data="{ shown: false }" x-intersect.once="shown = true">
                <h2 x-show="shown" x-transition.duration.2000 class="text-6xl max-w-xl">
                    Benefits for Life Essentials
                </h2>
                <p>
                    At Panda People, we believe in providing you with the types of benefits that enhance life and make work more rewarding.

                    We’ve partnered with trusted vendors to offer services that ease daily stresses, like finding quality daycare for your kids, paying your cellphone bill, and getting health, wellness, and vet services
                    that fit your needs and wallet.
                </p>
            </div>
            <div class="space-y-6">
                <h3 class="text-xl">Here's more what we offers:</h3>
                <ul x-data="{ activeAccordion: null }" class="divide-y">
                    @foreach (['Up to 20% off your rent' => '', 'Cellphone programs' => ''] as $item => $content)
                        <li class="py-6">
                            <button class="text-2xl" x-on:click="activeAccordion = activeAccordion == @js($loop->index) ? null : @js($loop->index)">{{ $item }}</button>
                            <div class="mt-6" x-show="activeAccordion == @js($loop->index)">
                                <p>We know rent is likely your greatest expense, so we’ve done something about it. By working with select landlords, we are helping people like you lower your rent by up to 20%.</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="flex flex-col lg:flex-row gap-6">
                    <p class="lg:w-1/2">Learn how to can sign up for supplemental benefits your employer can offer through Panda People. </p>
                    <div class="lg:w-1/2">
                        <x-link href="#" class="hover:bg-panda-green mx-auto" outlined>See Additional Benefits</x-link>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="bg-panda-green">
        <div class="px-[min(6.99vw,50px)] py-8 max-w-[1920px] mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3" x-data="{ shown: false }" x-intersect.once="shown = true">
                <h2 x-show="shown" x-transition.duration.2000 class="text-7xl lg:text-8xl md:col-span-2">
                    …and Purchases Essential to Life
                </h2>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <img class="-mt-40 md:-mt-60 lg:-mt-80 w-[100vw]" src="{{ asset('storage/assets/benefit-circles-2.png') }}" />
            <div x-data="{ shown: false }" x-intersect.once="shown = true" class="p-8 space-y-4 place-self-center -mt-20 lg:-mt-40">
                <h2 x-show="shown" x-transition.duration.2000 class="text-4xl">
                    We’re here to help you afford more of what brings you joy and everyday essentials.
                </h2>
                <h5 class="text-xl">
                    Find everyday deals on:
                </h5>
                <ul class="grid grid-cols-2">
                    @foreach ($categories as $category)
                        <li>
                            <x-a :href="route('deals.index', ['filter' => ['category_id' => $category->getKey()]])">{{ $category->name }}</x-a>
                        </li>
                    @endforeach
                </ul>
                <x-link outlined :href="route('deals.index')">
                    Discover more deals
                </x-link>
            </div>
        </div>
    </section>
</x-layouts.app>
