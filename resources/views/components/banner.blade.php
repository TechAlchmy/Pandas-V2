@props([
    'background' => null,
])
<div class="bg-cover bg-bottom h-[700px]" style="background-image: url('{{ $background }}');">
    <div class="container flex items-center space-x-4 mx-auto">
        <div class="p-10 w-full relative flex">
            <div class="w-3/4">
                <img src="{{ asset('storages/assets/circler-white.png') }}" alt="2 Circles " class="h-[600px]" />
                <div class="bottom-out-blade">
                    <h1 class="font-editorial text-[60px] font-thin text-white">Live. Grow. Play. <br> Panda.</h1>
                </div>
            </div>
            <div class="w-1/4 text-white relative">
                <p class="text-[50px] w-[700px] ml-[-60%] leading-[60px] py-10 ">
                    Explore a new world of benefits that help you grow
                </p>
                <p class="text-[20px]">
                    Welcome to your Panda People portal. Here, you’ll find a new world of benefits
                    curated to help you do what you love on your terms. We’re here to help you live
                    inquisitively, play, and explore more of life with less stress.
                </p>
                <x-button size="lg" color="white" outlined class="absolute bottom-0">
                    Learn More
                </x-button>
            </div>
        </div>
    </div>
</div>
