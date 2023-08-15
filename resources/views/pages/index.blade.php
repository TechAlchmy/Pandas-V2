@auth
    <x-layouts.app>
        <livewire:resources.user-resource.widgets.upsell-widget />
        <x-banner :background="asset('storage/banners/panda-main.png')" />
        <section class="px-[min(6.99vw,50px)] py-4">
            <x-hr />
            <div class="lg:flex justify-between gap-4">
                <h2 class="text-2xl lg:text-6xl font-editorial">
                    Deals on Daily<br /> Essentials
                </h2>
                <div>
                    <p>
                        Your day-to-day just got more affordable.
                    </p>
                    <x-link outlined href="/deals">
                        Discover more
                    </x-link>
                </div>
            </div>
            <div class="py-24"></div>
            @php
                $discounts = \App\Models\Discount::query()
                    ->with('categories')
                    ->take(5)
                    ->get();
            @endphp
            <div class="flex">
                @foreach ($discounts as $discount)
                    <div class="p-4">
                        <x-a href="/deals/{{ $discount->slug }}">
                            <h3 class="font-editorial text-5xl leading-[60px]">{{ $discount->name }}</h3>
                        </x-a>
                        <div>
                            <p class="uppercase text-2xl">Up to {{ $discount->percentage }}% off</p>
                            <p class="text-xl leading-7">24-hour access to primary care for less</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <x-hr />
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
