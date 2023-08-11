<x-layouts.app>
    <x-topbar />
    <div class="bg-black text-center text-white p-8 lg:flex lg:justify-between lg:items-center">
        <h2 class="text-6xl font-light">
            Now Offering Housing
        </h2>
        <div class="py-4 lg:py-0"></div>
        <div>
            <x-link href="/help" class="px-8 py-3 text-base rounded-[70%] border border-white">
                Learn More
            </x-link>
        </div>
    </div>
    <div class="flex p-8 justify-between">
        <h1 class="text-4xl lg:text-7xl">My Panda</h1>
        <p class="hidden lg:block">{{ auth()->user()->organization?->name }}</p>
    </div>
    <livewire:resources.user-resource.widgets.tabs />
    <x-footer />
</x-layouts.app>
