<?php
use function Laravel\Folio\{name};

name('deals.index');
?>
<x-layouts.app>
    <section class="px-[min(6.99vw,50px)] py-8 lg:flex">
        <h1 class="text-2xl lg:text-4xl">
            Live More with Panda-Powered Deals + Discounts
        </h1>
    </section>
    <section class="px-[min(6.99vw,50px)] py-8 bg-black">
        <div class="divide-y divide-white text-white font-editorial text-3xl">
            <div class="text-center p-8">
                Top Category
            </div>
            @php
                $categories = \App\Models\Category::query()
                    ->isRoot()
                    ->inRandomOrder()
                    ->take(5)
                    ->get();
            @endphp
            @foreach ($categories as $category)
                <div class="text-center p-8">
                    {{ $category->name }}
                </div>
            @endforeach
        </div>
    </section>
</x-layouts.app>
