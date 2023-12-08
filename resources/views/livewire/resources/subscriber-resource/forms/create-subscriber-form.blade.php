<?php

use function Livewire\Volt\{state, rules};

state(['email']);

rules(['email' => 'required|email']);

$submit = function () {
    $this->validate();

    \App\Models\Subscriber::query()->firstOrCreate(['email' => $this->email]);

    \Filament\Notifications\Notification::make()
        ->title('Subscribed!')
        ->success()
        ->send();

    $this->reset('email');
};
?>

<form wire:submit.prevent="submit" class="space-y-6 text-white">
    <h4 class="font-editorial">Panda People Newsletter</h4>
    <div class="flex items-center max-w-xs py-2 border-b border-white gap-x-1">
        <x-input type="email" class="text-lg placeholder-white" wire:model="email" placeholder="EMAIL" />
    </div>
    @error('email')
        <div>
            {{ $message }}
        </div>
    @enderror
    <x-button type="submit" outlined color="white" class="inline-block">
        Sign Up
    </x-button>
</form>
