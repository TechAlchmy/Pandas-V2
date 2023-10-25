<?php
use function Laravel\Folio\{middleware, name};
middleware(['auth']);
name('employer');
?>

<x-layouts.app for-employer>
    <div class="flex flex-col lg:flex-row mx-auto w-full max-w-[1920px] bg-black">
        <div class="mt-8 overflow-hidden">
            <img class="hidden md:block lg:hidden -ml-10 min-w-[125vw]"
                src="{{ asset('/storage/assets/employer-index-tablet.png') }}" />
            <img class="hidden mx-auto lg:block lg:px-8 2xl:px-0"
                src="{{ asset('/storage/assets/employer-index-desktop.png') }}" />
            <img class="md:hidden -ml-40 min-w-[150vw]" src="{{ asset('/storage/assets/employer-index-mobile.png') }}" />
            <div class="flex flex-col lg:flex-row-reverse">
                <div
                    class="-mt-32 -mr-10 md:-mr-20 md:-mt-20 lg:-mt-40 lg:-mr-10 lg:-ml-60 2xl:-mt-48 md:max-w-[90vw] md:mx-auto">
                    <img class="order-1 lg:order-0 2xl:min-w-[35vw]"
                        src="{{ asset('/storage/assets/employer-grow2.png') }}" />
                </div>
                <div>
                    <div class="w-4/5 lg:w-3/5 flex flex-col md:flex-row  p-[50px] mt-[50px] text-container">
                        <div class="flex flex-col gap-2 md:px-10" x-data="{ shown: false }"
                            x-intersect.once="shown = true">
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
    </div>
    <div class="px-[min(6.99vw,50px)] py-8">
        <div class="flex flex-col lg:flex-row mx-auto w-full max-w-[1920px]">
            <div>
                <h2 class="text-5xl font-editorial lg:text-7xl 2xl:text-9xl">What is Panda People</h2>
                <p>
                    Employee benefits aren’t black and white anymore. There’s a spectrum of perks that can make an
                    impact on
                    your employees’ sense of fulfillment and their ability to truly engage with their work.
                </p>
                <div>
                    <h3>Our Benefits</h3>
                    <div class="flex">
                        <ul class="list-disc">
                            <li>Housing Discount</li>
                            <li>Exclusive Cell Phone Plans</li>
                            <li>Childcare</li>
                            <li>Health & Mental Wellness</li>
                            <li>Groceries, Meals & Entertainment</li>
                            <li>Financial Wellness</li>
                        </ul>
                        <p>
                            Creating a company culture people truly appreciate starts with understanding how impactful
                            easing the cost of living can be on improving engagement and retention. With Panda People,
                            you get customized benefits that help you meet the needs of your people where they are.

                            Our curated benefits bring real impact at a low cost to employers while helping your people
                            live fuller life.
                        </p>
                    </div>
                </div>
            </div>
            <div class="-mr-64">
                <img ssclass="w-[125vw]" src="{{ asset('storage/assets/employer-index-whatispanda.png') }}" />
            </div>
        </div>

    </div>
</x-layouts.app>
