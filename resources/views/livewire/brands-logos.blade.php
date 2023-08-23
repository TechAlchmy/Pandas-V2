<div x-data="{ activeCategory: @entangle('activeCategory') }">

    <div @click="activeCategory = 'Health & Wellness'"
         class="border-t border-b border-black h-[120px] transition-colors flex items-center justify-center"
         :class="{ 'bg-black': activeCategory === 'Health & Wellness' }">
        <h2 class="font-editorial text-5xl leading-[70px] text-center"
            :class="{ 'text-white': activeCategory === 'Health & Wellness' }">Health & Wellness</h2>
    </div>

    <div @click="activeCategory = 'Groceries'"
         class="border-b border-black h-[120px] transition-colors flex items-center justify-center"
         :class="{ 'bg-black': activeCategory === 'Groceries' }">
        <h2 class="font-editorial text-5xl leading-[70px] text-center"
            :class="{ 'text-white': activeCategory === 'Groceries' }">Groceries</h2>
    </div>

    <div @click="activeCategory = 'Apparel'"
         class="border-b border-black h-[120px] transition-colors flex items-center justify-center"
         :class="{ 'bg-black': activeCategory === 'Apparel' }">
        <h2 class="font-editorial text-5xl leading-[70px] text-center"
            :class="{ 'text-white': activeCategory === 'Apparel' }">Apparel</h2>
    </div>

    <div @click="activeCategory = 'Entertainment'"
         class="border-b border-black h-[120px] transition-colors flex items-center justify-center"
         :class="{ 'bg-black': activeCategory === 'Entertainment' }">
        <h2 class="font-editorial text-5xl leading-[70px] text-center"
            :class="{ 'text-white': activeCategory === 'Entertainment' }">Entertainment</h2>
    </div>

    <div @click="activeCategory = 'Travel'"
         class="border-b border-black h-[120px] transition-colors flex items-center justify-center"
         :class="{ 'bg-black': activeCategory === 'Travel' }">
        <h2 class="font-editorial text-5xl leading-[70px] text-center"
            :class="{ 'text-white': activeCategory === 'Travel' }">Travel</h2>
    </div>

    <div x-show="activeCategory" class="logos-slider bg-black p-4">
    <template x-if="activeCategory === 'Health & Wellness'">
        <!-- Display logos for Health & Wellness here -->
    </template>

    <template x-if="activeCategory === 'Apparel'">
        <div class="swiper-container">
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
            
            <div class="swiper-pagination"></div>
        </div>
    </template>


    
</div>

</div>
