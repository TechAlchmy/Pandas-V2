@props([
    'background' => null,
])
<div class="bg-cover bg-bottom" style="background-image: url('{{ $background }}');">
    <div class="max-w-[1920px] mx-auto h-[700px]">
        <div class="relative h-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div></div>
                <div class="p-8 text-white space-y-6 lg:space-y-8 z-[1]" x-data="{ shown: false }" x-intersect.once="shown = true">
                    <h1 x-show="shown" x-transition.duration.2000 class="text-3xl lg:text-4xl font-editorial lg:max-w-sm">
                        Explore a new world of benefits that help <u>you</u> grow
                    </h1>
                    <div class="lg:ml-64 space-y-6 lg:space-y-8 lg:max-w-xs">
                        <p class="lg:text-lg">
                            Welcome to your Panda People portal.
                            Here, you’ll find a new world of benefits curated to help you do what you love on your terms.
                            We’re here to help you live inquisitively, play, and explore more of life with less stress.
                        </p>
                        <x-link href="/benefits" outlined color="white">View My Benefits</x-link>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 min-w-full">
                <img class="min-w-[10rem] -mb-16 lg:w-full lg:max-w-[75rem] xl:-mb-32" src="{{ getMediaPath('assets/circler-white.png') }}" alt="2 Circles " />
                <h4 class="text-black p-8 text-3xl lg:text-6xl xl:text-8xl font-light font-editorial">Live. Grow. Play. Panda.</h4>
            </div>
        </div>
    </div>
</div>
