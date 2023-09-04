<x-layouts.base {{ $attributes->twMerge(['bg-panda-green']) }}>
    <x-topbar.simple />
    <div class="h-32"></div>
    {{ $slot }}
    <x-footer />
</x-layouts.base>
