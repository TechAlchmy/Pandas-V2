<?php

?>

<x-layouts.app for-employer>
    <section class="flex flex-col lg:flex-row mx-auto w-full max-w-[1920px]">
        <div class="mt-8 overflow-hidden">
            <img class="hidden md:block lg:hidden -ml-10 min-w-[125vw]" src="{{ getMediaPath('assets/employer-company-tablet.png') }}" />
            <img class="hidden mx-auto lg:block lg:px-8 2xl:px-0" src="{{ getMediaPath('assets/employer-company-desktop.png') }}" />
            <img class="md:hidden -ml-40 min-w-[150vw]" src="{{ getMediaPath('assets/employer-company-mobile.png') }}" />
            <div class="flex flex-col lg:flex-row-reverse">
                <div class="p-8 mt-10 md:-mr-4 md:-mt-20 lg:-mt-24 lg:-mr-10 lg:-ml-60 2xl:-mt-48 md:max-w-xs lg:max-w-lg md:mx-auto">
                    <h2 class="text-4xl font-editorial">About Us</h2>
                    <p class="mt-8">
                        Panda People was founded with a vision of helping improve employee benefits offerings by going beyond the norm. Through strategic partnerships with employers and benefit consultants, Panda People offers, maintains, and manages comprehensive and customized benefits packages for companies and their employees.
                    </p>
                    {{-- <img class="order-1 lg:order-0 2xl:min-w-[35vw]" src="{{ getMediaPath('assets/employer-grow2.png') }}" /> --}}
                </div>
                <div>
                    <div class="w-4/5 lg:w-3/5 flex flex-col md:flex-row  p-[50px] mt-[50px] text-container">
                        <div class="flex flex-col gap-2 md:px-10" x-data="{ shown: false }" x-intersect.once="shown = true">
                            <p class="text-white font-aeonik" x-show="shown" x-transition.opacity.duration.2000>
                                Support your people’s growth with meaningful benefits that go beyond single-digit
                                discounts for exclusive gyms. At Panda People, we deliver benefits that promote cultures
                                of engagement and expansion by easing the cost of daily essentials, relieving stress,
                                and cultivating space to play.
                            </p>
                            <div>
                                <x-link outlined color="white" href="/employer/reesources">
                                    Schedule a demo today
                                </x-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="bg-panda-green">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <div class="-mt-40">
                <img src="{{ getMediaPath('assets/employer-company-what-one.png') }}" alt="Leadership" />
            </div>
            <div class="p-8">
                <p>
                    Blurb about the leadership team. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus consequat urna vitae ornare ullamcorper. Quisque nec ipsum a libero feugiat consequat. Fusce efficitur eu dui a sollicitudin. Maecenas nulla nisl, mollis. Sit amet, consectetur adipiscing elit. Vivamus consequat urna vitae ornare ullamcorper. Quisque nec ipsum a libero feugiat consequat. Fusce efficitur eu dui a sollicitudin. Maecenas nulla nisl, mollis.
                </p>
                <x-hr />
                <div class="space-y-6">
                    <h4 class="text-4xl font-editorial">Careers</h4>
                    <div class="grid grid-cols-1 gap-2 lg:grid-cols-2">
                        <p>
                            Join the revolution! We’re on a mission to transform employee benefits and make real change in real workforces. That means everyone at Panda People gets access to the same life-easing benefits we offer our clients (and top secret ones we’re testing out).s
                        </p>
                        <div>
                            <x-link outlined>Explore Open Roles</x-link>
                        </div>
                    </div>
                </div>
                <x-hr />
            </div>
        </div>
    </section>
    <section class="bg-black">
        <div class="w-full py-8 space-y-8 mx-auto max-w-[1920px] ">
            <div class="text-white">
                <h4 class="text-6xl text-left lg:text-center lg:-ml-80 font-editorial lg:text-7xl xl:text-8xl 2xl:text-9xl">Attract. Retain.</h4>
                <h4 class="text-6xl text-right lg:text-center lg:ml-80 font-editorial lg:text-7xl xl:text-8xl 2xl:text-9xl">Grow. Perform.</h4>
            </div>
            <x-hr class="border-white" />
        </div>
    </section>
</x-layouts.app>
