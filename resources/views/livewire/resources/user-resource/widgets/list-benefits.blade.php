<?php

use function Livewire\Volt\{state};

?>

<div>
    <div class="grid grid-cols-1">
        <div x-data="{ shown: false }" x-intersect.once="shown = true">
            <div class="text-center space-y-6" x-show="shown" x-transition.duration.2000>
                <h3 class="text-4xl lg:text-6xl font-editorial">
                    Panda's Benefits are Coming Soon...
                </h3>
            </div>
        </div>
    </div>
</div>
