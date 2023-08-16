<?php

use function Livewire\Volt\{state};

state('viewable');

$count = fn() => recentlyViewed()->create($this->viewable);

?>

<div x-init="setTimeout(() => $wire.count(), 3000)"></div>
