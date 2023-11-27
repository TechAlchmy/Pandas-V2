@props(['withText' => false])
<div class="flex items-center gap-4">
    @svg('logo', '', $attributes->twMerge(['h-7 md:h-10 w-auto text-black hover:animate-bounce'])->getAttributes())
    @if ($withText)
        <span class="hidden sm:inline-block lg:ml-6 sm:text-xl md:text-2xl lg:text-3xl">
            panda people
        </span>
    @endif
</div>
