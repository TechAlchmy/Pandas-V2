@props([
    'forEmployer' => false,
])
<x-layouts.base {{ $attributes->twMerge(['bg-panda-green']) }}>
    @if ($forEmployer)
        <x-topbar.employer />
    @else
        <x-topbar.simple />
    @endif
    <div class="h-32"></div>
    {{ $slot }}
    <x-footer />
</x-layouts.base>
