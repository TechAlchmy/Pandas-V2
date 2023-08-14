<div class="p-8">
    <form wire:submit.prevent="save">
        {{ $this->form }}
        <x-button outlined class="inline-block mt-8">
            Update
        </x-button>
    </form>
</div>
