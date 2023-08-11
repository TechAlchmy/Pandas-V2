<?php

use function Livewire\Volt\{state};

state(['selected' => 0]);
?>

<div class="grid grid-cols-4 p-8">
    <ul class="divide-y text-xl">
        @foreach (['My Details', 'My Preferences', 'My Orders'] as $menu)
            <li class="p-4" wire:key="{{ $loop->index }}">
                <button x-on:click="$wire.set('selected', {{ $loop->index }})">{{ $menu }}</button>
            </li>
        @endforeach
    </ul>
    <div class="col-span-3">
        {{ $selected }}
    </div>
</div>
