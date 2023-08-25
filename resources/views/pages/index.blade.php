@auth
    <x-layouts.app>
        <x-banner-upsell />
        <x-banner :background="asset('storage/banners/panda-main.png')" />
        <section class="px-[min(6.99vw,50px)] py-4" style="max-width: 1920px; margin:auto">
            <x-hr />
            <div class="lg:flex justify-between gap-4">
                <h2 class="text-2xl lg:text-6xl font-editorial">
                    Deals on Daily<br /> Essentials
                </h2>
                <div>
                    <p class="text-[20px]">
                        Your day-to-day just got more affordable.
                    </p>
                    <x-link outlined href="/deals">
                        Discover more
                    </x-link>
                </div>
            </div>
            <div class="py-24"></div>
            @php
                $categories = \App\Models\Category::query()
                    ->whereHas('discounts', fn($query) => $query->forOrganization(auth()->user()?->organization_id)->where('is_active', true))
                    ->withMax('discounts', 'percentage')
                    ->inRandomOrder()
                    ->take(5)
                    ->get();
            @endphp
            <div class="flex m-auto">
                @foreach ($categories as $category)
                    <div class="flex-1 p-4 flex flex-col justify-between">
                        <x-a href="/deals">
                            <h3 class="font-editorial text-5xl leading-[60px]">{{ $category->name }}</h3>
                        </x-a>
                        <div>
                            <p class="uppercase text-2xl">Up to {{ $category->discounts_max_percentage }}% off</p>
                            <p class="text-xl leading-7">24-hour access to primary care for less</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <x-hr />
        </section>
        <section class="bg-black ">
            <div>
                <div class="text-white flex space-x-8 px-[min(6.99vw,50px)] py-9 m-auto" style="max-width: 1920px;">
                    <div class="w-1/2">
                        <h3 class="font-editorial text-6xl leading-[70px]">Benefits for <br> Life Essentials</h3>
                    </div>
                    <div class="w-1/2 max-w-[700px] text-xl px-7">
                        <p class="font-aeonik">
                            At Panda People, we believe in real benefits that
                            make a difference. We know pretend perks only go so
                            far, so we’ve done something about it. Real people like
                            you deserve better, so we’re delivering benefits for life
                            essentials like housing, phone plans, and health + wellness.
                        </p>
                    </div>
                </div>
                <div class="text-white flex space-x-8">
                    <div class="flex-shrink-1 w-3/5 lef-0">
                        <div class="max-w-[900px]">
                            <img src="{{ asset('storage/assets/pandas-3-circle.png') }}" alt="3 different color of circles" class="ml-0" />
                        </div>

                    </div>
                    <div class="flex flex-col justify-center h-full m-auto">
                        <h1 class="font-editorial text-4xl leading-[40px]">Benefits with Panda</h1>
                        <ul class="font-aeonik list-disc list-inside p-5">
                            <li>Discounts on rent</li>
                            <li>Cellphone programs</li>
                            <li>Trusted child daycare services</li>
                            <li>Financial planning</li>
                            <li>Health + mental wellness</li>
                        </ul>
                        <x-link class="max-w-[200px] m-auto" color="white" outlined href="/deals">Learn More</x-link>

                        <div class="mt-10">
                            <p class="text-[10px]">*Not all deals/benefits available in all areas</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <section  class="px-[min(6.99vw,50px)] py-4 " style="max-width: 1920px; margin:auto">
            <div>
                <h1 class="font-editorial text-6xl leading-[70px] mt-9">Panda Partners with Brands You Know</h1>
            </div>
            <livewire:brands-logos />
            
        </section>
        <section class="px-[min(6.99vw,50px)] py-4" style="max-width: 1920px; margin:auto">
            <h1 class="font-editorial text-6xl leading-[70px]">Guides</h1>
            <x-hr />
            <div class="flex">
                <div class="w-1/3 p-7">
                    <img src="{{ asset('storage/assets/grey-bg.png') }}" alt="Panda images" class="my-5" />
                    <h1 class="font-editorial text-4xl leading-[40px] my-5">5 Ways to Save on Everyday Groceries</h1>
                    <p class="line-clamp-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
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
                    <x-link class="my-5" outlined href="/deals">Read Article</x-link>

                </div>
                <div class="w-1/3 p-7">
                    <img src="{{ asset('storage/assets/grey-bg.png') }}" alt="Panda images" class="my-5" />
                    <h1 class="font-editorial text-4xl leading-[40px] my-5">5 Ways to Save on Everyday Groceries</h1>
                    <p class="line-clamp-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
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
                    <x-link class="my-5" outlined href="/deals">Read Article</x-link>

                </div>
                <div class="w-1/3 p-7">
                    <img src="{{ asset('storage/assets/grey-bg.png') }}" alt="Panda images" class="my-5" />
                    <h1 class="font-editorial text-4xl leading-[40px] my-5">5 Ways to Save on Everyday Groceries</h1>
                    <p class="line-clamp-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
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
                    <x-link class="my-5" outlined href="/deals">Read Article</x-link>

                </div>

            </div>

        </section>

        <livewire:resources.contact-inquiry-resource.forms.contact-us-form />
    </x-layouts.app>
@endauth
@guest
    <x-layouts.base>
        <x-topbar.simple class="bg-white">
            <div class="flex gap-6 text-2xl">
                <x-a href="/login">
                    <p class="text-base lg:text-2xl">Member Sign In</p>
                </x-a>
                <x-a href="#">
                    <p class="text-base lg:text-2xl">Schedule a Demo</p>
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
