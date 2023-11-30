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
            <div class="relative hidden h-screen lg:flex">
                <div class="absolute top-[-5rem] h-screen -z-10">
                    <img class="" src="{{ getMediaPath('assets/benefits-hero-image.png') }}" />
                </div>
                <div class="grid grid-cols-4">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div class="space-y-6 lg:mt-8 lg:text-xs xl:text-base">
                        <p class="">
                            Real benefits for real people start with Panda. We’re here to help you thrive in ways that matter to you, so you can give more and get more when it’s go time—in life and work.
                        </p>
                        <p>
                            Explore the benefits your employer is providing through Panda People.
                        </p>
                        <x-link class="hover:bg-panda-green hover:border-transparent" outlined :href="route('dashboard', ['activeTab' => 1])">View My Benefits</x-link>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-6 lg:hidden lg:grid-cols-2" x-data="{ shown: false }" x-intersect.once="shown = true">
                <h1 x-show="shown" x-transition.duration.1500 class="max-w-xl text-3xl sm:text-6xl font-editorial">Get Ready to go beyond the basic</h1>
                <div class="space-y-6 lg:ml-56 xl:ml-80">
                    <p class="text-sm">
                        Real benefits for real people start with Panda. We’re here to help you thrive in ways that matters to you, so you can give more and get more when it’s go time—in life and work.
                    </p>
                    <p>
                        Explore the benefits your employer is providing through Panda People.
                    </p>
                    <x-link class="hover:bg-panda-green hover:border-transparent" outlined :href="route('dashboard', ['activeTab' => 1])">View My Benefits</x-link>
                </div>
            </div>
        </div>
        <img class="lg:hidden min-w-[125vw] -ml-20 lg:-ml-72 2xl:-ml-[42rem] -mt-8 md:-mt-16 lg:-mt-96" src="{{ getMediaPath('assets/benefit-circles.png') }}" alt="Collages images" />
    </section>
    <section class="px-[min(6.99vw,50px)] py-8 md:-mt-40 lg:mt-0">
        <div class="max-w-[1920px] mx-auto">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div x-data="{ shown: false }" x-intersect.once="shown = true">
                    <h2 x-show="shown" x-transition.duration.2000 class="max-w-xl text-6xl font-editorial">
                        Benefits for Life Essentials
                    </h2>
                    <p>
                        At Panda People, we believe in providing you with the types of benefits that enhance life and make work more rewarding.

                        We’ve partnered with trusted vendors to offer services that ease daily stresses, like finding quality daycare for your kids, paying your cellphone bill, and getting health, wellness, and vet
                        services
                        that fit your needs and wallet.
                    </p>
                </div>
                <div class="space-y-6">
                    <h3 class="text-xl">More of what we offer:</h3>
                    <ul x-data="{ activeAccordion: null }" class="divide-y">
                        @php
                            $items = [
                                'Up to 20% off your rent' => 'We know rent is likely your greatest expense, so we’ve done something about it. By working with select landlords, we are helping people like you lower your rent by up to 20%.',
                                'Cellphone programs' => 'Ditch your costly phone plan and get an Android smartphone at zero cost to you, including unlimited talk, text, data, and hotspot.',
                                'Trusted child daycare services' => '10% off KinderCare child daycare and early education. Our partners, Wonderschool, help you find childcare in their area that fits your needs and wallet.',
                                'Financial Planning' => 'We’ve partnered with Debt.com and over financial advisors to help you climb out of debt and improve other aspects of your financial wellness.',
                                'Health + Mental Wellness' => 'Get 24-hour access to primary care, therapy, and mental health services across the country',
                                'Televet Service' => 'Connect with a veterinary expert at any time, day or night, to get your companion the care they deserve',
                            ];
                        @endphp
                        @foreach ($items as $item => $content)
                            <li class="py-6">
                                <button class="text-2xl" x-on:click="activeAccordion = activeAccordion == @js($loop->index) ? null : @js($loop->index)">{{ $item }}</button>
                                <div class="mt-6" x-show="activeAccordion == @js($loop->index)">
                                    <p>{{ $content }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <div class="flex flex-col gap-6 lg:flex-row">
                        <p class="lg:w-1/2">Learn how to can sign up for supplemental benefits your employer can offer through Panda People. </p>
                        <div class="lg:w-1/2">
                            <x-link href="#" class="mx-auto hover:bg-panda-green hover:border-transparent" outlined>See Additional Benefits</x-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="bg-panda-green">
        <div class="px-[min(6.99vw,50px)] py-8">
            <div class="max-w-[1920px] mx-auto">
                <h2 x-show="shown" x-transition.duration.2000 class="text-2xl md:text-5xl lg:text-7xl xl:text-8xl 2xl:text-9xl md:col-span-2 font-editorial">
                    …and Purchases Essential to Life
                </h2>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div>
                <img class="lg:hidden -mt-16 sm:max-h-[30vh] mx-auto" src="{{ getMediaPath('assets/benefit-circles-mobile.png') }}" />
                <img class="hidden lg:block -mt-40 md:-mt-96 2xl:max-h-[70vh] 2xl:mx-auto object-cover" src="{{ getMediaPath('assets/benefit-circles-2.png') }}" />
            </div>
            <div x-data="{ shown: false }" x-intersect.once="shown = true" class="p-8 -mt-20 space-y-4 place-self-center lg:-mt-40 xl:-mt-20">
                <h2 x-show="shown" x-transition.duration.2000 class="text-4xl xl:text-6xl 2xl:text-7xl font-editorial">
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
