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
                                slidesPerView: 1,
                                spaceBetween: 20
                            },
                            // when window width is >= 480px
                            480: {
                                slidesPerView: 3,
                                spaceBetween: 30
                            },
                            640: {
                                slidesPerView: 6,
                                spaceBetween: 40
                            },
                            // when window width is >= 640px
                            768: {
                                slidesPerView: 8,
                                spaceBetween: 40
                            },
                        }
                    });">
                        <div class="swiper-wrapper">
                            @foreach ($category->brands as $brand)
                                <div class="swiper-slide py-4">
                                    <div class="grid">
                                        <img class="place-self-center max-w-[150px] max-h-24 invert" src="{{ $brand->getFirstMediaUrl('logo') }}" alt="{{ $brand->name }} Logo" />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </x-a>
        </div>
    @endforeach
</div>
