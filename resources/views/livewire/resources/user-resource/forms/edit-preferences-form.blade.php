<div class="p-4 lg:pt-0 lg:pb-0">
    <form wire:submit.prevent="save" class="border-t py-8">
        {{ $this->form }}
        <div class="flex justify-start">
            <x-button type="submit" size="lg" outlined class="inline-block mt-8 hover:bg-panda-green hover:border-transparent">
                Update
            </x-button>
        </div>
    </form>
    <x-filament-actions::modals />
</div>
