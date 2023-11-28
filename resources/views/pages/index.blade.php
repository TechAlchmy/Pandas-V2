@auth
    @php
        $categories = \App\Models\Category::query()
            ->where('is_active', true)
            ->withMax('discounts', 'percentage')
            ->withBrands(auth()->user()?->organization)
            ->inRandomOrder()
            ->take(5)
            ->get();
        
        $featuredDiscount = \App\Models\Discount::query()
            ->withBrand(auth()->user()?->organization)
            ->withVoucherType(auth()->user()?->organization)
            ->whereHas('featuredDeals')
            ->active()
            ->inRandomOrder()
            ->first();
    @endphp
    <x-layouts.app>
        <x-banner-upsell :record="$featuredDiscount" />
        <x-banner :background="getMediaPath('banners/panda-main.png')" />
        <section class="px-[min(6.99vw,50px)] py-4" style="max-width: 1920px; margin:auto">
            <x-hr />
            <div class="lg:flex justify-between gap-4">
                <h2 class="text-4xl lg:text-6xl font-editorial">
                    Deals on Daily Essentials
                </h2>
                <div class="space-y-6">
                    <p class="text-2xl">
                        Your day-to-day just got more affordable.
                    </p>
                    <x-link class="hover:bg-panda-green" outlined href="/deals">
                        Discover more
                    </x-link>
                </div>
            </div>
            <div class="py-8 lg:py-24"></div>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
                @foreach ($categories as $category)
                    <div class="flex-1 flex flex-col justify-between">
                        <x-a :href="route('deals.index', ['filter' => ['category_id' => $category->getKey()]])">
                            <h3 class="font-editorial text-3xl lg:text-4xl leading-[60px]">{{ $category->name }}</h3>
                        </x-a>
                        <x-a :href="route('deals.index', ['filter' => ['category_id' => $category->getKey()]])">
                            <div>
                                <p class="uppercase text-xl lg:text-2xl">Up to {{ $category->discounts_max_percentage }}% off</p>
                                <p class="text-md lg:text-xl leading-7">24-hour access to primary care for less</p>
                            </div>
                        </x-a>
                    </div>
                @endforeach
            </div>
            <x-hr />
        </section>
        <section class="bg-black text-white px-[min(6.99vw,50px)] py-8 overflow-x-hidden">
            <div class="space-y-6 max-w-[1920px] mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2" x-data="{ shown: false }" x-intersect.once="shown = true">
                        <h2 x-show="shown" x-transition.duration.2000 class="font-editorial text-6xl lg:text-8xl">
                            Benefits for Life Essentials
                        </h2>
                    </div>
                    <div class="text-white space-y-6 lg:space-y-8 z-[1]">
                        <p class="lg:text-lg">
                            At Panda People, we believe in real benefits that make a difference.
                            We know pretend perks only go so far, so we’ve done something about it.
                            Real people like you deserve better, so we’re delivering benefits for life essentials like housing, phone plans, and health + wellness.*
                        </p>
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <div class="lg:col-span-3">
                        <img class="-ml-52 min-w-[150vw] lg:min-w-[80vw] lg:max-w-md 2xl:-ml-96" src="{{ getMediaPath('assets/pandas-3-circle.png') }}" alt="3 different color of circles" />
                    </div>
                    <div class="space-y-6" x-data="{ shown: false }" x-intersect.once="shown = true">
                        <h1 x-show="shown" x-transition.duration.2000 class="font-editorial text-6xl">Benefits with Panda</h1>
                        <ul class="list-disc list-inside">
                            <li>Discounts on rent</li>
                            <li>Cellphone programs</li>
                            <li>Trusted child daycare services</li>
                            <li>Financial planning</li>
                            <li>Health + mental wellness</li>
                        </ul>
                        <x-link class="hover:bg-panda-green" color="white" outlined href="/deals">Learn More</x-link>
                        <p class="text-xs">*Not all deals/benefits available in all areas</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="px-[min(6.99vw,50px)] py-8 bg-neutral-200">
            <div class="max-w-[1920px] mx-auto">
                <h1 class="font-editorial text-6xl leading-[70px] mt-9">Panda Partners with Brands You Know</h1>
                <x-brand-logos :categories="$categories" />
            </div>
        </section>
        <section class="px-[min(6.99vw,50px)] py-8 max-w-[1920px] mx-auto">
            <h1 class="font-editorial text-6xl">Guides</h1>
            <x-hr />
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                @foreach (range(1, 3) as $i)
                    <div class="space-y-6">
                        <x-a :href="route('deals.index')">
                            <div class="relative">
                                <div class="pt-[65%]"></div>
                                <img class="absolute inset-0 object-cover w-full h-full"
                                    src="https://images.unsplash.com/photo-1454496522488-7a8e488e8606?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2952&q=80s" />
                            </div>
                        </x-a>
                        <x-a :href="route('deals.index')">
                            <h1 class="font-editorial text-4xl leading-[40px] my-5">5 Ways to Save on Everyday Groceries</h1>
                        </x-a>
                        <p class="line-clamp-3">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            Vivamus consequat urna vitae ornare ullamcorper. Quisque
                            nec ipsum a libero feugiat consequat. Fusce efficitur eu dui
                            a sollicitudin…. Maecenas nulla nisl, mollis et sapien sed,
                            tincidunt sodales est. Vivamus sed scelerisque mi, auctor accumsa
                            n massa. Vestibulum vitae enim consectetur, semper massa at, vulputate
                            justo. Maecenas ligula ligula, dictum ac urna elementum, elementum imperdiet
                            diam. Pellentesque gravida sollicitudin vestibulum. Vivamus dignissim
                            laoreet tortor, condimentum elementum ante maximus eu. Sed iaculis mi
                            quis velit egestas volutpat. Vestibulum ante ipsum primis in faucibus
                            orci luctus. et ultrices posuere cubilia curae; Aenean ut tortor dignissim,
                            sollicitudin turpis a, egestas augue. Duis eget hendrerit elit. Duis
                            lacinia sed lectus a rhoncus. Quisque iaculis mi sapien, eu malesuada
                            ligula eleifend nec. Sed venenatis fringilla justo id aliquam.
                            Praesent lectus odio, pretium id ligula quis, blandit sagittis justo.
                        </p>
                        <x-link class="hover:bg-panda-green" outlined href="/deals">Read Article</x-link>
                    </div>
                @endforeach
            </div>
        </section>

        <livewire:resources.contact-inquiry-resource.forms.contact-us-form testimonial />
    </x-layouts.app>
@endauth
@guest
    <x-layouts.base>
        <x-topbar.simple class="bg-white">
            <div class="flex gap-6 text-2xl">
                <x-a :href="route('login')">
                    <span class="">Member Sign In</span>
                </x-a>
                <x-a href="#">
                    <span class="">Schedule a Demo</span>
                </x-a>
            </div>
        </x-topbar.simple>
        <div class="h-16 lg:h-32"></div>
        <section class="w-full pb-[min(184px,25.5vw)] lg:pb-52 flex flex-col">
            <x-guest-content />
        </section>
        <x-footer />
    </x-layouts.base>
@endguest
