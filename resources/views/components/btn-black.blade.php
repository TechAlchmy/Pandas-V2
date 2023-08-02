<div {{ $attributes->merge(['class' => '']) }}>
    <div class="relative w-fit">
        <a href="{{ $link ?? '#' }}">
            <img src="{{ asset('storage/assets/btn-black.png') }}" alt="Collages images" class="max-w-[260px]"/>
            <div class="absolute inset-0 flex items-center justify-center">
                <p class="text-white">  {{ $slot }}</p>
            </div>
        </a>
    </div>
</div>
