<x-layouts.app>
    <x-topbar />
    <livewire:resources.user-resource.widgets.upsell-widget />
    <div class="flex p-8 justify-between">
        <h1 class="text-4xl lg:text-7xl">My Panda</h1>
        <p class="hidden lg:block">{{ auth()->user()->organization?->name }}</p>
    </div>
    <livewire:resources.user-resource.widgets.tabs />
    <x-footer />
</x-layouts.app>
