<div>
    <form wire:submit.prevent="resetPassword">
        {{ $this->form }}
        <x-button type="submit" size="lg" outlined class="inline-block mt-8">
            {{ __('Reset Password') }}
        </x-button>
    </form>
</div>
