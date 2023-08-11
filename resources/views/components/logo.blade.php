@props(['withText' => false])
<div class="flex items-center">
    @svg('logo', '', $attributes->twMerge(['h-8 md:h-10 w-auto text-black'])->getAttributes())
    @if ($withText)
        <span class="hidden sm:inline-block ml-2 sm:text-xl md:text-2xl">
            panda people
        </span>
    @endif
</div>
