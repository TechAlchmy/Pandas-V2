<?php

use function Livewire\Volt\{state};

$logout = function () {
    auth()->logout();

    request()
        ->session()
        ->invalidate();

    request()
        ->session()
        ->regenerateToken();

    return redirect('/');
};

?>

<a wire:click.prevent="logout"
    class="relative flex cursor-default select-none hover:bg-neutral-100 items-center rounded px-2 py-1.5 text-sm outline-none transition-colors focus:bg-accent focus:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-2">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
        <polyline points="16 17 21 12 16 7"></polyline>
        <line x1="21" x2="9" y1="12" y2="12"></line>
    </svg>
    <span>Log out</span>
    <span class="ml-auto text-xs tracking-widest opacity-60">⇧⌘Q</span>
</a>
