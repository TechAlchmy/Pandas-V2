<x-layouts.base {{ $attributes }}>
    <x-topbar />
    @auth
        <x-topbar.extension />
    @endauth
    {{ $slot }}
    <x-footer.extra />
</x-layouts.base>
