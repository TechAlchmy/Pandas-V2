<x-filament-panels::page.simple class="max-w-xl mx-auto px-[min(6.99vw,50px)] py-8">
    <x-filament-panels::form wire:submit="register">
        {{ $this->form }}

        <x-filament-panels::form.actions :actions="$this->getCachedFormActions()" :full-width="$this->hasFullWidthFormActions()" />
    </x-filament-panels::form>
</x-filament-panels::page.simple>
