<x-layouts.base>
    <x-topbar />
    @auth
        <x-topbar.extension />
    @endauth
    {{ $slot }}
    <livewire:footer />
</x-layouts.base>
