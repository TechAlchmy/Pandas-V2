<div class="p-8">
    <form wire:submit.prevent="save">
        {{ $this->form }}
        <div class="flex justify-end">
            <x-button outlined class="inline-block mt-8">
                Update
            </x-button>
        </div>
    </form>
</div>
