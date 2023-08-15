<div>
    <form wire:submit="authenticate">
        {{ $this->form }}
        <x-button type="submit" size="lg" outlined class="inline-block mt-4">
            Login
        </x-button>
    </form>
</div>
