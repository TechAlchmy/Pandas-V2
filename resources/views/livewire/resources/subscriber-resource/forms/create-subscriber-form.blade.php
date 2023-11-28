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
    <h4 class="font-editorial">Panda People Newsletter</h4>
    <div class="py-2 border-b border-white flex gap-x-1 items-center max-w-xs">
        <x-input type="email" class="text-lg placeholder-white" wire:model="email" placeholder="EMAIL" />
    </div>
    <x-button type="submit" outlined color="white" class="inline-block">
        Sign Up
    </x-button>
</form>
