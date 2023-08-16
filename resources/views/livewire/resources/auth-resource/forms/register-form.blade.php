<div>
    <form wire:submit.prevent="register">
        {{ $this->form }}
        <x-button type="submit" size="lg" outlined class="inline-block mt-8">
            Register
        </x-button>
    </form>
</div>
