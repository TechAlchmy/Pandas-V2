<x-layouts.base {{ $attributes }} class="flex flex-col min-h-screen">
    <x-topbar />
    @auth
        <x-topbar.extension />
    @endauth
    {{ $slot }}
    <div class="flex-grow"></div>
    <x-footer.extra />
</x-layouts.base>
