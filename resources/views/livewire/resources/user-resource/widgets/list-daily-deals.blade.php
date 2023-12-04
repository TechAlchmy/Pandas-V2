<?php

use function Livewire\Volt\{computed};

$categories = computed(function () {
    return \App\Models\Category::query()
        ->where('is_active', true)
        ->get();
});

?>
<div class="grid grid-cols-1 gap-6 lg:grid-cols-2 bg-panda-green">
    <div class="grid grid-cols-1">
        <div class="place-self-center">
            <img class="max-h-[40rem]" src="{{ getMediaPath('assets/list-daily-deals.png') }}" />
        </div>
    </div>
    <div class="grid grid-cols-1">
        <div class="p-4 space-y-4 lg:place-self-center">
            <h2 class="text-3xl font-editorial">
                We’re here to help you afford more of what brings you joy and everyday essentials.
            </h2>
            <h5 class="text-xl">
                Find everyday deals on:
            </h5>
            <ul class="grid grid-cols-2 lg:max-w-sm">
                @foreach ($this->categories as $category)
                    <li>
                        <x-a :href="route('deals.index', ['filter' => ['category_id' => $category->getKey()]])">{{ $category->name }}</x-a>
                    </li>
                @endforeach
            </ul>
            <div class="flex justify-end md:block">
                <x-link class="hover:bg-black hover:text-white" outlined :href="route('deals.index')">
                    Discover more deals
                </x-link>
            </div>
        </div>
    </div>
</div>
