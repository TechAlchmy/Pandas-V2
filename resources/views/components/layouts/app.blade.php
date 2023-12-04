@props([
    'forEmployer' => false,
])
<x-layouts.base {{ $attributes }} class="flex flex-col min-h-screen">
    @if ($forEmployer)
        <x-topbar.employer />
    @else
        <x-topbar />
    @endif
    <div class="h-24 lg:h-28"></div>
    @auth
        <x-topbar.extension />
    @endauth
    {{ $slot }}
    <div class="flex-grow"></div>
    <x-footer.extra />
</x-layouts.base>
