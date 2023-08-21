<?php
$updateClicks = fn($id) => \App\Models\Discount::find($id)?->increment('clicks');
?>
<div x-on:deal-clicked.window="$wire.updateClicks($event.detail.id)"></div>
