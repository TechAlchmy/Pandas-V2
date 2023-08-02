<x-app-layout>
    <x-banner-upsell/>
    <x-banner :link="asset('storage/banners/panda-main.png')">
    </x-banner>
    <section class="container px-[min(6.99vw,50px)] ">
        <x-break/>
        <x-black-line/>
        <div class="flex justify-between mt-10">
            <h1 class="text-[100px] font-editorial leading-[110px] ">Deals on Daily <br> Essentials</h1>
            <div>
                <p class="mb-10">Your day-to-day just got more affordable.</p>
                <x-btn-white class="">Learn More</x-btn-white>
            </div>
        </div>

    </section>
    <x-footer/>
</x-app-layout>
