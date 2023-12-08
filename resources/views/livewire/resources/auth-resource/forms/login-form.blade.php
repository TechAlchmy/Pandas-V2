<div>
    <form wire:submit="authenticate">
        {{ $this->form }}
        <x-button type="submit" outlined class="inline-block mt-8 hover:bg-black hover:text-white">
            Login
        </x-button>
    </form>
</div>
