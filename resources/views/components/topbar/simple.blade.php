<nav {{ $attributes->twMerge(['w-full flex bg-panda-green flex-col fixed z-30']) }}>
    <div class="w-full flex flex-row justify-between px-[min(6.99vw,50px)] lg:pl-12 h-[75px] lg:h-[100px] items-center mx-auto" style="max-width:1920px">
        <x-a href="/">
            <x-logo with-text />
            {{-- <x-application-logo  decoding="async" data-nimg="1" class="max-h-[28.5px] lg:max-h-[43px]" loading="eager" style="color:transparent"/> --}}
        </x-a>
        {{ $slot }}
    </div>
</nav>
