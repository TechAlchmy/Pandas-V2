<div class="flex flex-col lg:flex-row mx-auto w-full max-w-[1920px]">
    <div class="pb-4 mt-8 overflow-hidden">
        <img class="hidden md:block lg:hidden -ml-24 min-w-[125vw]" src="{{ getMediaPath('assets/guest-index-image-tablet.png') }}" />
        <img class="hidden mx-auto lg:block lg:px-8 2xl:px-0" src="{{ getMediaPath('assets/guest-index-image.png') }}" />
        <img class="md:hidden -ml-[7.5rem] min-w-[150vw]" src="{{ getMediaPath('assets/guest-index-image-mobile.png') }}" />
        <div class="flex flex-col lg:flex-row-reverse">
            <div class="-mt-32 -mr-20 md:-mt-20 lg:-mt-40 lg:-ml-60 2xl:-mt-48 md:max-w-[90vw] md:mx-auto">
                <img class="order-1 lg:order-0 2xl:min-w-[25vw]" src="{{ getMediaPath('assets/guest-grow.png') }}" />
            </div>
            <div>
                <div class="w-4/5 lg:w-3/5 flex flex-col md:flex-row  p-[50px] mt-[50px] text-container">
                    <div class="flex flex-col md:px-10" x-data="{ shown: false }" x-intersect.once="shown = true">
                        <p class="mb-5 font-aeonik text=[20px] uppercase" x-show="shown" x-transition.duration.1000>What is Panda People?</p>
                        <p class="font-aeonik" x-show="shown" x-transition.opacity.duration.2000>Real benefits for real people start with Panda.
                            We’re here to help you thrive in ways that matter
                            to you, so you can give more when it’s go time. Get
                            started on your journey to more life, more growth,
                            and more play.
                        </p>
                    </div>
                    <div class="">
                        <p class="mb-5 text-2xl uppercase max-md:mt-10 font-aeonik">OUR SITES</p>
                        <div class="h-[120px] guest-buttons space-y-4">
                            <x-link size="lg" href="/employer" outlined class="px-8 hover:bg-panda-green hover:border-transparent">For Employers</x-link>
                            <x-link size="lg" href="/dashboard" outlined class="px-8 hover:bg-panda-green hover:border-transparent">For Employees</x-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
