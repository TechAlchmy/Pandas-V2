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
            <div class="relative hidden lg:flex">
                <div class="absolute top-[-5rem] -z-10">
                    <img class="" src="{{ getMediaPath('assets/benefits-hero-image.png') }}" />
                </div>
                <div class="grid grid-cols-4">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div class="space-y-6 lg:mt-8 text-base lg:text-base  xl:text-lg">
                        <p>
                            Real benefits for real people start with Panda. We’re here to help you thrive in ways that matter to you, so you can give more and get more when it’s go time—in life and work.
                        </p>
                        <p>
                            Explore the benefits your employer is providing through Panda People.
                        </p>
                        <x-link class="hover:border-transparent hover:bg-panda-green" outlined :href="route('dashboard', ['activeTab' => 1])">View My Benefits</x-link>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-6 lg:hidden lg:grid-cols-2" x-data="{ shown: false }" x-intersect.once="shown = true">
                <h1 x-show="shown" x-transition.duration.1500 class="max-w-xl text-4xl md:text-4xl lg:text-5xl xl:text-6xl 2xl:text-8xl">Get Ready to Go</h1>
                <div class="w-[calc(100%+2*min(6.99vw,50px))] mx-auto ml-auto ml-[max(-6.99vw,-50px)] -mt-12 z-[-1] overflow-x-hidden -mb-20">
                    <img class="lg:hidden min-w-[125vw] -ml-12 lg:-ml-72 2xl:-ml-[42rem]" src="{{ getMediaPath('assets/benefit-circles.png') }}" alt="Collages images" />
                </div>
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
        
    </section>
    <section class="px-[min(6.99vw,50px)] py-8 2xl:mt-[40rem] xl:mt-64 lg:mt-48">
        <div class="max-w-[1920px] mx-auto">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div x-data="{ shown: false }" x-intersect.once="shown = true">
                    <h2 x-show="shown" x-transition.duration.2000 class="hidden lg:block max-w-xl text-4xl md:text-4xl lg:text-5xl xl:text-6xl 2xl:text-[5.5rem]">
                        Benefits for Life Essentials
                    </h2>
                    <h2 x-show="shown" x-transition.duration.2000 class="lg:hidden max-w-xl text-4xl md:text-4xl lg:text-5xl xl:text-6xl 2xl:text-7xl mb-6">
                        Benefits for
                    </h2>
                    <div class="space-y-6 lg:max-w-sm lg:mt-6 text-base lg:text-base  xl:text-lg">
                        <p class="">
                            At Panda People, we believe in providing you with the types of benefits that enhance life and make work more rewarding.
                        </p>
                        <p>
                            We’ve partnered with trusted vendors to offer services that ease daily stresses, like finding quality daycare for your kids, paying your cellphone bill, and getting health, wellness, and vet
                            services
                            that fit your needs and wallet.
                        </p>
                    </div>
                </div>
                <div class="space-y-6">
                    <h3 class="text-xl">Here’s more of what we can offer:</h3>
                    <ul x-data="{ activeAccordion: 0 }" class="divide-y border-y">
                        @php
                            $items = [
                                'Up to 20% off your rent' => 'We know rent is likely your greatest expense, so we’ve done something about it. By working with select landlords, we are helping people like you lower your rent by up to 20%.',
                                'Cellphone programs' => 'Ditch your costly phone plan and get an Android smartphone at zero cost to you, including unlimited talk, text, data, and hotspot.',
                                'Trusted child daycare services' => '10% off KinderCare child daycare and early education.<br /> Our partners, Wonderschool, help you find childcare in their area that fits your needs and wallet.',
                                'Financial Planning' => 'We’ve partnered with Debt.com and other financial advisors to help you climb out of debt and improve other aspects of your financial wellness.',
                                'Health + Mental Wellness' => 'Get 24-hour access to primary care, therapy, and mental health services across the country',
                                'Televet Service' => 'Connect with a veterinary expert at any time, day or night, to get your companion the care they deserve',
                            ];
                        @endphp
                        @foreach ($items as $item => $content)
                            <li class="py-6">
                                <button class="text-xl" x-on:click="activeAccordion = activeAccordion == @js($loop->index) ? null : @js($loop->index)">{{ $item }}</button>
                                <div class="mt-6" x-show="activeAccordion == @js($loop->index)">
                                    <div class="lg:max-w-sm">{!! $content !!}</div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section class="relative">
        <div class="absolute w-[100%] bg-panda-green h-[100%]"></div>
        <img class="absolute top-[-200px] hidden lg:block lg:min-w-[50vw] 2xl:mx-auto object-cover w-[200px]" src="{{ getMediaPath('assets/benefit-circles-2-black-circle.png') }}"/>
        <div class="absolute w-[100%] bg-panda-green h-[100%] overflow-hidden">
            <img class="absolute top-[-200px] hidden lg:block lg:min-w-[50vw] 2xl:mx-auto object-cover w-[200px]" src="{{ getMediaPath('assets/benefit-circles-2-image.png') }}"/>
        </div>
        
        <div class="px-[min(6.99vw,50px)] py-8 relative pt-16">            
            <div class="mx-auto max-w-[1920px]">
                <h2 x-show="shown" x-transition.duration.2000 class="hidden lg:block text-4xl md:text-4xl lg:text-5xl xl:text-6xl 2xl:text-[5.5rem] md:col-span-2">
                    …and Purchases Essential to Life
                </h2>
                <h2 x-show="shown" x-transition.duration.2000 class="lg:hidden text-4xl md:text-4xl lg:text-5xl xl:text-6xl 2xl:text-7xl md:col-span-2 mb-6">
                    …and Purchases
                </h2>
            </div>
        </div>
        <img class="relative w-[70%] lg:hidden -mt-16 sm:max-h-[70vh] mx-auto" src="{{ getMediaPath('assets/benefit-circles-mobile.png') }}" />
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 relative pb-16">
            <div></div>
            <div x-data="{ shown: false }" x-intersect.once="shown = true" class="p-8 space-y-6 xl:space-y-12 place-self-center xl:-mt-0">
                <h2 x-show="shown" x-transition.duration.2000 class="text-3xl xl:text-4xl 2xl:text-5xl">
                We're here to help you pay for everyday essentials and afford more of what brings you joy
                </h2>
                <h5 class="text-xl">
                    Find everyday deals on:
                </h5>
                <ul class="md:max-w-xs grid grid-cols-2">
                    @foreach ($categories as $category)
                        <li>
                            <x-a :href="route('deals.index', ['filter' => ['category_id' => $category->getKey()]])">{{ $category->name }}</x-a>
                        </li>
                    @endforeach
                </ul>
                <x-link outlined :href="route('deals.index')">
                    Discover more
                </x-link>
            </div>
        </div>
    </section>
</x-layouts.app>
