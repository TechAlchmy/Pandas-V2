@props(['categories'])

<div class="divide-y">
    @foreach ($categories as $category)
        <div class="relative py-6 group">
            <h2 class="font-editorial text-5xl leading-[70px] text-center">{{ $category->name }}</h2>
            <x-a :href="route('deals.index', ['filter' => ['category_id' => $category->getKey()]])">
                <div class="invisible opacity-0 group-hover:opacity-100 transition-opacity group-hover:visible absolute inset-y-0 w-full bg-black">
                    <div class="swiper" x-init="new Swiper('.swiper', {
                        speed: 400,
                        spaceBetween: 20,
                        slidesPerView: 5,
                        spaceBetween: 10,
                        rewind: true,
                        breakpoints: {
                            // when window width is >= 320px
                            320: {
                                slidesPerView: 6,
                                spaceBetween: 20
                            },
                            // when window width is >= 480px
                            480: {
                                slidesPerView: 7,
                                spaceBetween: 30
                            },
                            // when window width is >= 640px
                            640: {
                                slidesPerView: 8,
                                spaceBetween: 40
                            }
                        }
                    });">
                        <div class="swiper-wrapper">
                            @foreach ($category->brands as $brand)
                                <div class="swiper-slide py-4">
                                    <img class="max-w-[150px] max-h-24 invert" src="{{ $brand->getFirstMediaUrl('logo') }}" alt="{{ $brand->name }} Logo" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </x-a>
        </div>
    @endforeach
</div>

{{-- Use this as a backup --}}
{{-- <div class="swiper-container">
    <div class="swiper-wrapper">
        <div class="swiper-slide bg-black flex items-center justify-center">
            <img src="{{ asset('storage/logo/adidas-white.png') }}" alt="Adidas Logo" class="max-w-[150px] max-h-[100px]">
        </div>
        <div class="swiper-slide bg-black flex items-center justify-center">
            <img src="{{ asset('storage/logo/boss.png') }}" alt="Hugo Boss Logo" class="max-w-[150px] max-h-[100px]">
        </div>

        <div class="swiper-slide bg-black flex items-center justify-center">
            <img src="{{ asset('storage/logo/nb.png') }}" alt="NB Logo" class="max-w-[150px] max-h-[100px]">
        </div>

        <div class="swiper-slide bg-black flex items-center justify-center">
            <img src="{{ asset('storage/logo/nike_white.png') }}" alt="Nike Logo" class="max-w-[150px] max-h-[100px]">
        </div>

        <div class="swiper-slide bg-black flex items-center justify-center">
            <img src="{{ asset('storage/logo/puma.png') }}" alt="Puma Logo" class="max-w-[150px] max-h-[100px]">
        </div>

        <div class="swiper-slide bg-black flex items-center justify-center">
            <img src="{{ asset('storage/logo/reebok.png') }}" alt="Reebok Logo" class="max-w-[150px] max-h-[100px]">
        </div>

        <div class="swiper-slide bg-black flex items-center justify-center">
            <img src="{{ asset('storage/logo/skechers.png') }}" alt="Skechers Logo" class="max-w-[150px] max-h-[100px]">
        </div>
    </div>
</div> --}}
