<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <x-header>
            @section('description','')
            @section('keywords','')
            @section('title','Panda-Guest')
        </x-header>
    </head>
    <body class="antialiased">
       <nav class="w-full flex flex-col bg-white sticky top-0 z-30">
        @if (Route::has('login'))
            <div class="w-full flex flex-row justify-between px-[min(6.99vw,50px)] lg:pl-12 h-[75px] lg:h-[100px] items-center mx-auto"
                style="max-width:1920px"><a
                    href="/"><x-application-logo  decoding="async" data-nimg="1" class="max-h-[28.5px] lg:max-h-[43px]" loading="eager" style="color:transparent"/></a>
                <div class=" gap-[40px] flex">
                    <a href="{{ route('login') }}">
                        <p class="font-aeonik text-[22px] whitespace-nowrap  hover:nav-active">Member Sign In</p>
                    </a>
                    <a href="">
                        <p class="font-aeonik text-[22px] whitespace-nowrap  hover:nav-active">Schedule a Demo</p>
                    </a>
                </div>
            </div>
        @endif
        </nav>
        <section class="w-full pb-[min(184px,25.5vw)] lg:pb-52 flex flex-col">
            <x-guest-content/>
        </section>
        <x-footer/>
    </body>
</html>



