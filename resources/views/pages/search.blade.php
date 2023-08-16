<?php
use function Laravel\Folio\{name};

name('search');
?>
@php
    $q = request('q');
@endphp
<x-layouts.app>
    <section class="px-[min(6.99vw,50px)] mx-auto py-8 text-center font-light">
        <span class="text-6xl">
            No results for "{{ $q }}"<br />
        </span>
    </section>
</x-layouts.app>
