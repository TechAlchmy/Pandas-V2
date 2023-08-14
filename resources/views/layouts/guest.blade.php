<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <x-header>
            @section('description','')
            @section('keywords','')
            @section('title','Panda')
        </x-header>
    </head>
    <body class="panda-green-bg antialiased">
        <nav class="w-full flex flex-col panda-green-bg sticky top-0 z-30 ">
            <div class="w-full flex flex-row justify-between px-[min(6.99vw,50px)] lg:pl-12 h-[75px] lg:h-[100px] items-center mx-auto" style="max-width:1920px"><a href="/"><x-application-logo  decoding="async" data-nimg="1" class="max-h-[28.5px] lg:max-h-[43px]" loading="eager" style="color:transparent"/></a>
            </div>
        </nav>
        <section class="w-full pb-[min(184px,25.5vw)] hidden lg:flex flex-col">
            <div class="flex mx-auto" style="max-width: 1920px;">
                <div class="w-1/2 flex flex-col justify-center relative">
                    <div>
                        <img src="{{ asset('storage/assets/pandas-black-grey.png') }}" alt="Panda images" class=""/>
                    </div>
                    <div class="bottom-out">
                        <h1 class="font-editorial text-[60px] font-thin">Live. Grow. <br> Play. <br> Panda.</h1>
                    </div>
                </div>
                <div class="w-1/2 flex p-11 flex-col justify-center max-w-[600px]">
                    {{ $slot }}
                </div>
            </div>
        </section>

        <section class=" lg:hidden w-full pb-[min(184px,25.5vw)] ">
            <div class=" mx-auto flex-col" style="max-width: 1920px;">
                <div class="w-full flex p-11 flex-col justify-center max-w-[600px] m-auto">
                    
                    {{ $slot }}
                    
                </div>
                <div class="w-full flex flex-col justify-center relative">
                    <div>
                        <img src="{{ asset('storage/assets/pandas-black-grey.png') }}" alt="Panda images" class=""/>
                    </div>
                    <div class="bottom-out">
                        <h1 class="font-editorial text-[min(80px,9.2vw)] font-thin">Live. Grow. <br> Play. <br class="hidden lg:block"> Panda.</h1>
                    </div>
                </div>
                
            </div>
        </section>
        <x-footer/>
        @livewireScripts
    </body>
</html>
