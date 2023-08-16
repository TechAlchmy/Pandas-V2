<x-layouts.base>
    <x-topbar />
    @auth
        <x-topbar.extension />
    @endauth
    {{ $slot }}
    <x-footer.extra />
</x-layouts.base>
