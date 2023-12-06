@props([
    'forEmployer' => false,
])
<x-layouts.base {{ $attributes }} class="flex flex-col min-h-screen">
    @if ($forEmployer)
        <x-topbar.employer />
        <div class="h-24 lg:h-28"></div>
    @else
        <x-topbar />
        @auth
            <div class="h-24 lg:h-28"></div>
            <x-topbar.extension />
        @endauth
    @endif
    {{ $slot }}
    <div class="flex-grow"></div>
    <x-footer.extra />
</x-layouts.base>
