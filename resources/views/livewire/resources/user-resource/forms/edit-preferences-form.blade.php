<div class="p-4 lg:p-8">
    <form wire:submit.prevent="save" class="border-t lg:mt-[9.5rem] py-8">
        {{ $this->form }}
        <div class="flex justify-start">
            <x-button type="submit" size="lg" outlined class="inline-block mt-8 hover:bg-panda-green hover:border-transparent">
                Update
            </x-button>
        </div>
    </form>
    <x-filament-actions::modals />
</div>
