<?php
use function Laravel\Folio\{middleware, name};
name('employer');
?>

<x-layouts.app for-employer>
    <section class="bg-black">
        <div class="flex flex-col lg:flex-row mx-auto w-full max-w-[1920px]">
            <div class="py-8 mt-8 overflow-hidden">
                <img class="hidden md:block lg:hidden -ml-10 min-w-[125vw]" src="{{ getMediaPath('assets/employer-index-tablet.png') }}" />
                <img class="hidden mx-auto lg:block lg:px-8 2xl:px-0" src="{{ getMediaPath('assets/employer-index-desktop.png') }}" />
                <img class="md:hidden -ml-40 min-w-[150vw]" src="{{ getMediaPath('assets/employer-index-mobile.png') }}" />
                <div class="flex flex-col lg:flex-row-reverse">
                    <div class="-mt-32 -mr-10 md:-mr-20 md:-mt-20 lg:-mt-40 lg:-mr-10 lg:-ml-60 2xl:-mt-48 2xl:-mr-0 md:max-w-[90vw] md:mx-auto">
                        <img class="order-1 lg:order-0 2xl:min-w-[35vw]" src="{{ getMediaPath('assets/employer-grow2.png') }}" />
                    </div>
                    <div>
                        <div class="w-4/5 lg:w-3/5 flex flex-col md:flex-row  p-[50px] mt-[50px] text-container">
                            <div class="flex flex-col gap-6 md:px-10" x-data="{ shown: false }" x-intersect.once="shown = true">
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
    </section>
    <div class="">
        <div class="flex flex-col w-full mx-auto overflow-hidden lg:flex-row">
            <div class="space-y-24 px-[min(6.99vw,50px)] py-8 2xl:ml-60">
                <div class="space-y-6">
                    <h2 class="text-5xl font-editorial lg:text-7xl 2xl:text-8xl">What is Panda People</h2>
                    <p>
                        Employee benefits aren’t black and white anymore. There’s a spectrum of perks that can make an
                        impact on
                        your employees’ sense of fulfillment and their ability to truly engage with their work.
                    </p>
                </div>
                <div class="hidden max-w-xl mt-40 lg:block">
                    <h3 class="text-4xl font-editorial">Our Benefits</h3>
                    <div class="grid grid-cols-2 gap-1">
                        <ul class="list-disc">
                            <li>Housing Discount</li>
                            <li>Exclusive Cell Phone Plans</li>
                            <li>Childcare</li>
                            <li>Health & Mental Wellness</li>
                            <li>Groceries, Meals & Entertainment</li>
                            <li>Financial Wellness</li>
                        </ul>
                        <div class="space-y-6">
                            <p>
                                Creating a company culture people truly appreciate starts with understanding how
                                impactful
                                easing the cost of living can be on improving engagement and retention. With Panda
                                People,
                                you get customized benefits that help you meet the needs of your people where they are.

                                Our curated benefits bring real impact at a low cost to employers while helping your
                                people
                                live fuller life.
                            </p>
                            <x-link href="employer/benefits" class="bg-panda-green">View Benefits</x-link>
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
            <div class="grid grid-cols-1 gap-1">
                <ul class="list-disc">
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

                        Our curated benefits bring real impact at a low cost to employers while helping your people
                        live fuller life.
                    </p>
                    <x-link href="employer/benefits" class="bg-panda-green">View Benefits</x-link>
                </div>
            </div>
        </div>
    </div>
    <section class="px-[min(6.99vw)] py-8 space-y-8 mx-auto max-w-[1920px] w-full">
        <x-hr />
        <div class="">
            <h4 class="text-6xl text-left md:text-center md:-ml-40 lg:-ml-80 font-editorial lg:text-9xl">Giving More.</h4>
            <h4 class="text-6xl text-right md:text-center md:ml-40 lg:ml-80 font-editorial lg:text-9xl">Getting More.</h4>
        </div>
    </section>
    <div class="bg-panda-green">
        <div class="px-[min(6.99vw,50px)] py-8 max-w-[1920px] mx-auto">
            <div class="grid grid-cols-1 gap-6 lg:flex">
                <img class="lg:w-3/5" src="{{ getMediaPath('assets/employer-why-panda.png') }}" />
                <div class="space-y-6 lg:w-2/5 place-self-center">
                    <h3 class="text-7xl xl:text-8xl 2xl:text-9xl font-editorial">Why Panda People</h3>
                    <p class="lg:text-lg 2xl:text-2xl">
                        We only offer targeted benefits that have a positive impact on engaging, retaining, and
                        attracting employees where they live, work, and play—from discounts on rent to zero-cost cell
                        phone plans to exclusive entertainment savings.
                        <br />
                        <br />
                        Panda People’s relationships with local and national vendors mean we can customize benefits that
                        make measurable impacts.
                    </p>
                    <div>
                        <x-link outlined href="/employer/resources">Get Started Today</x-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="px-[min(6.99vw,50px)] py-8 max-w-[1920px] mx-auto">
        <x-hr />
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
            <div>
                <x-link outlined href="/employer/resources">Schedule a demo</x-link>
            </div>
            <div></div>
            <p>We work directly with you, your HR team, and trusted vendors to create benefits packages that draw down on essential costs for employees and make CFOs happy (or as close to happy as CFOs can get).</p>
            <p>With benefits like these, there’s a reason why employers who partner with Panda see engagement improve, turnover slow, and their businesses grow.</p>
        </div>
        <div class="lg:max-w-[80vw]">
            <h3 class="mt-40 text-4xl md:text-6xl lg:text-8xl font-editorial 2xl:text-9xl">
                Setting your company apart takes more, but it doesn’t have to cost more.
            </h3>
        </div>
        <x-hr />
    </section>
    <section class="px-[min(6.99vw,50px)] py-8 bg-neutral-300 grid min-h-[50vh]">
        <h5 class="place-self-center">Placeholder for stats</h5>
    </section>
    <section class="max-w-[1920px] mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div>
                <div class="relative min-h-[10rem] md:h-full w-full bg-cover" style="background-image: url({{ getMediaPath('assets/contact-us-banner.png') }})">
                    <div x-data="@js(['index' => 0, 'testimonials' => [['Test 1', 'This is good!'], ['Test 2', 'Panda has helped me!'], ['Test 3', 'This is the benefits that I have wanted'], ['Test 1', 'This is is awesome for you employees']]])" x-init="setInterval(() => {
                        if (index + 1 >= testimonials.length) {
                            index = 0;
                        } else {
                            index++;
                        }
                    }, 5000)" class="absolute inset-0 p-6 space-y-4 text-white">
                        <h3 class="text-4xl lg:text-6xl font-editorial" x-transition x-text="testimonials[index][1]"></h3>
                        <p x-text="testimonials[index][0]" x-transition>
                        </p>
                    </div>
                </div>
            </div>
            <div class="p-8 space-y-4">
                <h2 class="text-6xl font-light font-editorial">Let’s Show You How Panda Makes a Difference</h2>
                <p>
                    Schedule a demo with a benefits expert and learn how reducing your workforce’s cost of living means:
                </p>
                <ul class="list-disc">
                    <li>Improved retention</li>
                    <li>An attractive company culture</li>
                    <li>Your people giving more when it’s go time</li>
                </ul>
                <x-link outlined href="employer/resources">Get Started Today</x-link>
            </div>
        </div>
    </section>
    <section class="bg-black">
        <div class="w-full py-8 space-y-8 mx-auto max-w-[1920px] ">
            <div class="text-white">
                <h4 class="text-6xl text-left lg:text-center lg:-ml-80 font-editorial lg:text-9xl">Attract. Retain.</h4>
                <h4 class="text-6xl text-right lg:text-center lg:ml-80 font-editorial lg:text-9xl">Grow. Perform.</h4>
            </div>
            <x-hr class="border-white" />
        </div>
    </section>
</x-layouts.app>
