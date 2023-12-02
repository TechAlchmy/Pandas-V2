<?php

?>

<x-layouts.app for-employer>
    <section class="flex flex-col lg:flex-row mx-auto w-full max-w-[1920px]">
        <div class="mt-8 overflow-hidden">
            <img class="hidden mx-auto lg:block lg:px-8 2xl:px-0" src="{{ getMediaPath('assets/employer-company-desktop.png') }}" />
            <img class="lg:hidden -ml-10 min-w-[125vw]" src="{{ getMediaPath('assets/employer-company-tablet.png') }}" />
            {{-- <img class="md:hidden -ml-40 min-w-[150vw]" src="{{ getMediaPath('assets/employer-company-mobile.png') }}" /> --}}
            <div class="flex flex-col lg:flex-row-reverse">
                <div class="p-8 mt-10 md:-mr-4 md:-mt-20 lg:-mt-24 lg:-mr-10 lg:-ml-60 2xl:-mt-48 md:max-w-xs lg:max-w-lg md:mx-auto">
                    <h2 class="text-4xl font-editorial">About Us</h2>
                    <p class="mt-8">
                        Panda People was founded with a vision of helping improve employee benefits offerings by going beyond the norm. Through strategic partnerships with employers and benefit consultants, Panda People offers, maintains, and manages comprehensive and customized benefits packages for companies and their employees.
                    </p>
                    {{-- <img class="order-1 lg:order-0 2xl:min-w-[35vw]" src="{{ getMediaPath('assets/employer-grow2.png') }}" /> --}}
                </div>
                <div>
                </div>
            </div>
        </div>
    </section>
    <section class="bg-panda-green">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 max-w-[1920px] mx-auto">
            <div class="md:-mt-40">
                <img src="{{ getMediaPath('assets/employer-company-what-one.png') }}" alt="Leadership" />
            </div>
            <div class="flex flex-col justify-center w-full p-8 space-y-6">
                <p class="lg:text-lg xl:text-xl 2xl:text-2xl">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus consequat urna vitae ornare ullamcorper. Quisque nec ipsum a libero feugiat consequat. Fusce efficitur eu dui a sollicitudin. Maecenas nulla nisl, mollis. Sit amet, consectetur adipiscing elit. Vivamus consequat urna vitae ornare ullamcorper. Quisque nec ipsum a libero feugiat consequat. Fusce efficitur eu dui a sollicitudin. Maecenas nulla nisl, mollis.
                </p>
                <x-hr />
                <div class="space-y-6">
                    <h4 class="text-4xl font-editorial xl:text-6xl 2xl:text-7xl">Careers</h4>
                    <div class="grid grid-cols-1 gap-2 lg:grid-cols-2">
                        <p class="xl:text-md 2xl:text-lg">
                            Join the revolution! We’re on a mission to transform employee benefits and make real change in real workforces. That means everyone at Panda People gets access to the same life-easing benefits we offer our clients (and top secret ones we’re testing out).
                        </p>
                        <div class="text-right">
                            <x-link class="hover:bg-black hover:text-white" outlined>Explore Open Roles</x-link>
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
                <h4 class="text-6xl text-left md:text-center md:-ml-60 lg:-ml-80 font-editorial lg:text-7xl xl:text-8xl 2xl:text-9xl">Attract. Retain.</h4>
                <h4 class="text-6xl text-right md:text-center md:ml-60 lg:ml-80 font-editorial lg:text-7xl xl:text-8xl 2xl:text-9xl">Grow. Perform.</h4>
            </div>
        </div>
        <x-hr class="border-white" />
    </section>
</x-layouts.app>
