<div x-data="{ activeCategory: null }">

    @foreach ($categories as $category)
        <button @click="activeCategory = @js($category->getKey())"
            class="inline-block w-full border-t border-b border-black h-[120px] transition-colors flex items-center justify-center"
            :class="{ 'bg-black': activeCategory == @js($category->getKey()) }">
            <h2 class="font-editorial text-5xl leading-[70px] text-center"
                :class="{ 'text-white': activeCategory == @js($category->getKey()) }">{{ $category->name }}</h2>
        </button>
    @endforeach

    <div x-show="activeCategory" class="logos-slider bg-black p-4">
        @foreach ($categories as $category)
            <div x-show="activeCategory == @js($category->getKey())">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        @foreach ($category->brands as $brand)
                            <div class="swiper-slide bg-black flex items-center justify-center">
                                <img src="{{ $brand->getFirstMediaUrl('logo') }}" alt="{{ $brand->name }} Logo" class="invert max-w-[150px] max-h-[100px]">
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        @endforeach
    </div>
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
