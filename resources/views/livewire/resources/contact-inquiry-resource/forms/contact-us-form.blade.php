<div>
    <form wire:submit.prevent="create">
        {{ $this->form }}

        <div class="flex justify-end">
            <x-button outlined class="inline-block mt-8">
                Update
            </x-button>
        </div>
    </form>
</div>
