<div class="p-4 lg:p-8">
    <form wire:submit.prevent="save">
        {{ $this->form }}
        <div class="flex justify-end">
            <x-button type="submit" size="lg" outlined class="inline-block mt-8">
                Update
            </x-button>
        </div>
    </form>
    <x-filament-actions::modals />
</div>
