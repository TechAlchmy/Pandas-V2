<?php
use function Laravel\Folio\{middleware, name};
name('employer');
?>

<x-layouts.app for-employer>
    <section class="bg-black">
        <div class="flex flex-col lg:flex-row mx-auto w-full max-w-[1920px]">
            <div class="py-8 mt-8 overflow-hidden">
                {{-- <img class="hidden md:block lg:hidden -ml-10 min-w-[125vw]" src="{{ getMediaPath('assets/employer-index-tablet.png') }}" /> --}}
                <img class="hidden mx-auto md:block md:px-8 lg:px-12 2xl:px-0" src="{{ getMediaPath('assets/employer-index-desktop.png') }}" />
                <img class="md:hidden -ml-[7.5rem] min-w-[150vw]" src="{{ getMediaPath('assets/employer-index-mobile.png') }}" />
                <div class="flex flex-col gap-12 p-4 lg:ml-20 xl:ml-28 md:px-10 lg:max-w-md xl:max-w-lg lg:-mt-56 xl:-mt-72" x-data="{ shown: false }" x-intersect.once="shown = true">
                    <p class="text-white" x-show="shown" x-transition.opacity.duration.2000>
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
    </section>
    <div class="">
        <div class="flex flex-col w-full mx-auto overflow-hidden lg:flex-row max-w-[1920px]">
            <div class="space-y-24 px-[min(6.99vw,50px)] py-8">
                <div class="space-y-6">
                    <h2 class="text-4xl font-editorial lg:text-7xl 2xl:text-8xl">What is Panda People?</h2>
                    <p class="lg:text-lg xl:text-xl 2xl:text-2xl lg:max-w-sm lg:ml-20 xl:ml-28">
                        Employee benefits aren’t black and white anymore. There’s a spectrum of perks that can make an
                        impact on
                        your employees’ sense of fulfillment and their ability to truly engage with their work.
                    </p>
                </div>
                <div class="hidden mt-40 lg:block lg:ml-20 xl:ml-28">
                    <h3 class="text-4xl font-editorial">Our Benefits</h3>
                    <div class="grid grid-cols-2 gap-1 max-w-7xl">
                        <ul class="list-disc list-inside 2xl:text-lg">
                            <li>Housing Discount</li>
                            <li>Exclusive Cell Phone Plans</li>
                            <li>Childcare</li>
                            <li>Health & Mental Wellness</li>
                            <li>Groceries, Meals & Entertainment</li>
                            <li>Financial Wellness</li>
                        </ul>
                        <div class="space-y-6">
                            <p class="">
                                Creating a company culture people truly appreciate starts with understanding how
                                impactful
                                easing the cost of living can be on improving engagement and retention. With Panda
                                People,
                                you get customized benefits that help you meet the needs of your people where they are.
                            </p>
                            <p>
                                Our curated benefits bring real impact at a low cost to employers while helping your
                                people live fuller lives.
                            </p>
                            <x-link href="employer/benefits" outlined class="hover:bg-panda-green hover:border-transparent">View Benefits</x-link>
                        </div>
                    </div>
                </div>
            </div>
            <div class="-mt-12 overflow-x-hidden -mr-96 lg:-mr-64 xl:-mr-72 lg:-mt-28">
                <img class="w-[125vw] lg:w-[101vw]" src="{{ getMediaPath('assets/employer-index-whatispanda.png') }}" />
            </div>
        </div>
        <div class="lg:hidden px-[min(6.99vw,50px)]">
            <h3 class="text-4xl font-editorial">Our Benefits</h3>
            <div class="grid grid-cols-1 gap-4">
                <ul class="list-disc list-inside">
                    <li>Housing Discount</li>
                    <li>Exclusive Cell Phone Plans</li>
                    <li>Childcare</li>
                    <li>Health & Mental Wellness</li>
                    <li>Groceries, Meals & Entertainment</li>
                    <li>Financial Wellness</li>
                </ul>
                <div class="space-y-6">
                    <p>
                        Creating a company culture people truly appreciate starts with understanding how impactful
                        easing the cost of living can be on improving engagement and retention. With Panda People,
                        you get customized benefits that help you meet the needs of your people where they are.
                    </p>
                    <p>
                        Our curated benefits bring real impact at a low cost to employers while helping your people
                        live fuller life.
                    </p>
                    <x-link href="employer/benefits" outlined class="hover:bg-black hover:text-white">View Benefits</x-link>
                </div>
            </div>
        </div>
    </div>
    <section class="px-[min(6.99vw)] py-8 space-y-8 mx-auto max-w-[1920px] w-full">
        <x-hr />
        <div class="">
            <h4 class="text-5xl text-left md:text-center md:-ml-40 lg:-ml-80 font-editorial lg:text-8xl">Giving More.</h4>
            <h4 class="text-5xl text-right md:text-center md:ml-40 lg:ml-80 font-editorial lg:text-8xl">Getting More.</h4>
        </div>
    </section>
    <div class="bg-panda-green">
        <div class="px-[min(6.99vw,50px)] py-8 max-w-[1920px] mx-auto">
            <div class="grid grid-cols-1 gap-6 lg:flex">
                <img class="lg:w-3/5" src="{{ getMediaPath('assets/employer-why-panda.png') }}" />
                <div class="max-w-lg space-y-6 lg:w-2/5 place-self-center">
                    <h3 class="text-7xl xl:text-8xl 2xl:text-9xl font-editorial">Why Panda People</h3>
                    <p class="lg:text-lg 2xl:text-2xl">
                        We <u>only</u> offer targeted benefits that have a positive impact on engaging, retaining, and
                        attracting employees where they live, work, and play—from discounts on rent to zero-cost cell
                        phone plans to exclusive entertainment savings.
                    </p>
                    <p class="lg:text-lg 2xl:text-2xl">
                        Panda People’s relationships with local and national vendors mean we can customize benefits that
                        make measurable impacts.
                    </p>
                    <div>
                        <x-link class="hover:bg-black hover:text-white" outlined href="/employer/resources">Get Started Today</x-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="px-[min(6.99vw,50px)] py-8 max-w-[1920px] mx-auto">
        <x-hr />
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
            <div>
                <x-link class="hover:border-transparent hover:bg-panda-green" outlined href="/employer/resources">Schedule a demo</x-link>
            </div>
            <div></div>
            <p>We work directly with you, your HR team, and trusted vendors to create benefits packages that draw down on essential costs for employees and make CFOs happy (or as close to happy as CFOs can get).</p>
            <p>With benefits like these, there’s a reason why employers who partner with Panda see engagement improve, turnover slow, and their businesses grow.</p>
        </div>
        <div class="lg:max-w-[80vw]">
            <h3 class="mt-20 text-3xl lg:mt-40 md:text-6xl xl:text-7xl font-editorial 2xl:text-8xl">
                Setting your company apart takes more, but it doesn’t have to <u>cost</u> more.
            </h3>
        </div>
        <x-hr />
    </section>
    {{-- <section class="px-[min(6.99vw,50px)] py-8 bg-neutral-300 grid min-h-[50vh]">
        <h5 class="place-self-center">Placeholder for stats</h5>
    </section> --}}
    <section class="w-full sm:max-w-[1920px] mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div>
                <div class="relative min-h-[400px] md:h-full w-full bg-cover" style="background-image: url({{ getMediaPath('assets/contact-us-banner.png') }})">
                    <div x-data="{
                        ...@js(['testimonials' => [['Test 1', 'This is good!'], ['Test 2', 'Panda has helped me!'], ['Test 3', 'This is the benefits that I have wanted'], ['Test 1', 'This is is awesome for you employees']]]),
                        index: 0,
                    }" class="absolute inset-0 flex flex-col justify-between p-6 space-y-4">
                        <div class="space-y-4">
                            <h3 class="text-4xl lg:text-6xl font-editorial" x-transition x-text="testimonials[index][1]"></h3>
                            <p class="hidden sm:block" x-text="`- ${testimonials[index][0]}`" x-transition>
                            </p>
                        </div>
                        <div class="hidden sm:block">
                            <button class="group" x-on:click="
                            if (index - 1 > 0) {
                                index--;
                            } else {
                                index = testimonials.length - 1;
                            }">
                                @svg('arrow', 'h-12 rotate-180 -mx-3 group-hover:hidden')
                                @svg('arrow-hover', 'h-12 rotate-180 -mx-3 hidden group-hover:block')
                            </button>
                            <button class="group" x-on:click="if (index + 1 >= testimonials.length) {
                            index = 0;
                        } else {
                            index++;
                        }">
                                @svg('arrow', 'h-12 -mx-3 group-hover:hidden')
                                @svg('arrow-hover', 'h-12 -mx-3 hidden group-hover:block')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hidden sm:block p-8 space-y-6 bg-white">
                <h2 class="text-6xl font-light font-editorial">Let’s Show You<br />How Panda Makes a Difference</h2>
                <x-hr />
                <p class="lg:text-lg xl:text-xl">
                    Schedule a demo with a benefits expert and<br />learn how reducing your workforce’s cost of<br /> living means:
                </p>
                <ul class="list-disc list-inside">
                    <li>Improved retention</li>
                    <li>An attractive company culture</li>
                    <li>Your people giving more when it’s go time</li>
                </ul>
                <x-link class="hover:border-transparent hover:bg-panda-green" outlined href="employer/resources">Get Started Today</x-link>
                <x-hr />
            </div>
        </div>
    </section>
    <section class="bg-neutral-200 sm:bg-black">
        <div class="w-full py-8 space-y-8 mx-auto max-w-[1920px] ">
            <div class="text-black sm:text-white">
                <h4 class="text-5xl text-left md:text-center md:-ml-60 lg:-ml-80 font-editorial lg:text-6xl xl:text-7xl 2xl:text-8xl">Attract. Retain.</h4>
                <h4 class="text-5xl text-right md:text-center md:ml-60 lg:ml-80 font-editorial lg:text-6xl xl:text-7xl 2xl:text-8xl">Grow. Perform.</h4>
            </div>
        </div>
        <!-- <x-hr class="border-white" /> -->
    </section>
</x-layouts.app>
