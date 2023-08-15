@props(['withText' => false])
<div class="flex items-center gap-4">
    @svg('logo', '', $attributes->twMerge(['h-8 md:h-12 w-auto text-black'])->getAttributes())
    @if ($withText)
        <span class="hidden sm:inline-block ml-6 sm:text-xl md:text-2xl lg:text-3xl">
            panda people
        </span>
    @endif
</div>
