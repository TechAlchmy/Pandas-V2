<?php

use function Livewire\Volt\{computed};

$categories = computed(function () {
    return \App\Models\Category::query()
        ->where('is_active', true)
        ->get();
});

?>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 bg-panda-green">
    <div class="pt-[100%] bg-cover bg-center" style="background-image: url({{ asset('storage/assets/list-daily-deals.png') }})">
    </div>
    <div class="p-8 space-y-4">
        <h2 class="text-3xl">
            We’re here to help you afford more of what brings you joy and everyday essentials.
        </h2>
        <p>
            Find everyday deals on:
        </p>
        <ul class="grid grid-cols-2">
            @foreach ($this->categories as $category)
                <li>
                    <x-a :href="route('deals.index', ['filter' => ['category_id' => $category->getKey()]])">{{ $category->name }}</x-a>
                </li>
            @endforeach
        </ul>
        <x-link outlined :href="route('deals.index')">
            Discover more deals
        </x-link>
    </div>
</div>
