<div>
    <form wire:submit.prevent="request">
        {{ $this->form }}
        <x-button type="submit" size="lg" outlined class="inline-block mt-8 hover:bg-black hover:text-white">
            {{ __('Send Password Reset') }}
        </x-button>
    </form>
</div>
