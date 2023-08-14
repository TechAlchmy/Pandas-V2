<?php

use function Livewire\Volt\{state};

state(['email']);

$submit = function () {
    \App\Models\Subscriber::query()->firstOrCreate(['email' => $this->email]);

    \Filament\Notifications\Notification::make()
        ->title('Subscribed!')
        ->success()
        ->send();
};
?>

<form wire:submit.prevent="submit" class="text-white space-y-6">
    <h4>Panda People</h4>
    <input type="email" wire:model="email" class="block w-full bg-transparent border-b border-white text-white" placeholder="EMAIL" />
    <x-button type="submit" outlined color="white" class="inline-block">
        Sign Up
    </x-button>
</form>
