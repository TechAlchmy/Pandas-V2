<x-layouts.base {{ $attributes }}>
    <x-topbar.simple />
    <div class="h-32"></div>
    {{ $slot }}
    <x-footer />
</x-layouts.base>
