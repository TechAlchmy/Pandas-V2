<x-app-layout>
    <x-banner-upsell/>
    <x-banner :background="asset('storage/banners/panda-main.png')">
    </x-banner>
    <section class="container px-[min(6.99vw,50px)] ">
        <x-break/>
        <x-black-line/>
        <div class="flex justify-between mt-10">
            <h1 class="text-[100px] font-editorial leading-[110px] ">Deals on Daily <br> Essentials</h1>
            <div>
                <p class="mb-10">Your day-to-day just got more affordable.</p>
                <x-button size="lg" outlined>Learn More</x-button>
            </div>
        </div>
        <x-break :height="'200px'"/>

        <div class="flex mx-auto px-5">
            <div class="flex-1 p-4 flex flex-col justify-between">
                <h1 class="font-editorial text-[50px] leading-[60px]">Health & Wellness</h1>
                <div>
                    <p class="uppercase font-aeonik text-[23px]">Up to 20% off</p>
                    <p class="font-aeonik text-[20px] leading-[28px]">24-hour access to primary care for less</p>
                </div>
            </div>

            <div class="flex-1 p-4 flex flex-col justify-between">
                <h1 class="font-editorial text-[50px] leading-[60px]">Groceries</h1>
                <div>
                    <p class="uppercase font-aeonik text-[23px]">15% off</p>
                    <p class="font-aeonik text-[20px] leading-[28px]">Fill your fridge with storewide discounts</p>
                </div>
            </div>

            <div class="flex-1 p-4 flex flex-col justify-between">
                <h1 class="font-editorial text-[50px] leading-[60px]">Apparel</h1>
                <div>
                    <p class="uppercase font-aeonik text-[23px]">Up to 20% off</p>
                    <p class="font-aeonik text-[20px] leading-[28px]">Brands you know, scrubs, and workwear</p>
                </div>
            </div>

            <div class="flex-1 p-4 flex flex-col justify-between">
                <h1 class="font-editorial text-[50px] leading-[60px]">Entertainment</h1>
                <div>
                    <p class="uppercase font-aeonik text-[23px]">Dining, Daytrips, + More</p>
                    <p class="font-aeonik text-[20px] leading-[28px]">Enjoy your favorite restaurants, movie theaters, and live events</p>
                </div>
            </div>

            <div class="flex-1 p-4 flex flex-col justify-between">
                <h1 class="font-editorial text-[50px] leading-[60px]">Travel</h1>
                <div>
                    <p class="uppercase font-aeonik text-[23px]">Jetset for Less</p>
                    <p class="font-aeonik text-[20px] leading-[28px]">Exclusive packages to top destinations</p>
                </div>
            </div>
        </div>
        <x-break :height="'20px'"/>
        <x-black-line/>
    </section>

    <section class="container px-[min(6.99vw,50px)] ">
        <div class="bg:black h-[500px]" style="height: 500px; bacground:black">
            <div class="flex justify-between mt-10">
                <h1 class="text-[100px] font-editorial leading-[110px] ">Deals on Daily <br> Essentials</h1>
                <div>
                    <p class="mb-10">asdasdas dsad sa dasd asd asd.</p>
                    <x-button size="lg" outlined>Learn More</x-button>
                </div>
            </div>
        </div>
    </section>
  


    <x-footer/>
</x-app-layout>
