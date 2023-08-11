@props(['withText' => false])
<div class="flex items-center">
    @svg('logo', 'h-8 md:h-10 w-auto')
    @if ($withText)
        <span class="hidden sm:inline-block ml-2 sm:text-xl md:text-2xl">
            panda people
        </span>
    @endif
</div>
