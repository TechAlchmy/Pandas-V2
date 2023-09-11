<div class="flex flex-col lg:flex-row mx-auto w-full max-w-[1920px]">
    <div class="w-full">
        <div class=" flex justify-start  px-[min(8%,50px)]" x-data="{ shown: false }" x-intersect.once="shown = true">
            <h1 id="dynamicColorText" x-show="shown" x-transition.duration.2000 class="font-editorial text-[min(80px,9.2vw)] lg:text-8xl xl:text-9xl mt-12 mr-10 absolute lg:leading-[130px] p-1">
                Explore a
                <br class="max-xl:hidden">
                new
                <br class="hidden max-xl:block">
                world
                <br class="max-xl:hidden">
                of benefits...
            </h1>
        </div>
        <div id="imageContainer" class="flex max-h-[660px] justify-center">
            <img src="{{ asset('storage/banners/banner-guest.png') }}" alt="Collages images" class="max-w-[1500px] h-auto " />
        </div>
    </div>

</div>
<div class="mx-auto w-full px-[min(5%,30px)] xl:px-0 max-w-[1920px]">
    <div class="mx-auto max-w-screen-2xl">
        <div class="flex flex-row-reverse content-conteiner">
            <div class="ellipse-container">
                <svg viewbox="0 0 200 200" class="svg-style -mt-16">
                    <ellipse cx="95" cy="106" rx="90" ry="35" style="fill:none;stroke:black;stroke-width:0.4"></ellipse>
                </svg>
                <div x-data="{ shown: false }" x-intersect.once="shown = true" class="-mt-16">
                    <h3 x-show="shown" x-transition.opacity.duration.2000 class="text-[min(80px,9.2vw)] lg:text-8xl font-editorial lg:leading-[130px] text-pink">
                        that help <br> people grow.
                    </h3>
                </div>
            </div>

            <div class="w-4/5 lg:w-3/5 flex flex-col md:flex-row  p-[50px] mt-[50px] text-container">
                <div class=" flex flex-col md:px-10" x-data="{ shown: false }" x-intersect.once="shown = true">
                    <p class="mb-5 font-aeonik text=[20px] uppercase" x-show="shown" x-transition.duration.1000>What is Panda People?</p>
                    <p class="font-aeonik" x-show="shown" x-transition.opacity.duration.2000>Real benefits for real people start with Panda.
                        We’re here to help you thrive in ways that matter
                        to you, so you can give more when it’s go time. Get
                        started on your journey to more life, more growth,
                        and more play.
                    </p>
                </div>
                <div class="">
                    <p class="mb-5 max-md:mt-10 font-aeonik text-2xl uppercase">OUR SITES</p>
                    <div class="h-[120px] guest-buttons space-y-4">
                        <x-link size="lg" href="/dashboard" class="bg-black text-white px-8 hover:bg-panda-green">For Employers</x-link>
                        <x-link size="lg" href="/dashboard" class="bg-black text-white px-8 hover:bg-panda-green">For Employees</x-link>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
