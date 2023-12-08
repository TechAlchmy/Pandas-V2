<?php
?>

<x-layouts.app for-employer>
    <livewire:resources.employer-inquiry-resource.forms.employer-inquiry-form />
    @if (false)
        <section class="px-[min(6.99vw,50px)] py-8 max-w-[1920px] mx-auto">
            <h1 class="text-6xl font-editorial">Resources</h1>
            <x-hr />
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                @foreach (range(1, 3) as $i)
                    <div class="space-y-6">
                        <x-a :href="route('deals.index')">
                            <div class="relative">
                                <div class="pt-[65%]"></div>
                                <img class="absolute inset-0 object-cover w-full h-full" src="https://images.unsplash.com/photo-1454496522488-7a8e488e8606?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2952&q=80s" />
                            </div>
                        </x-a>
                        <x-a :href="route('deals.index')">
                            <h1 class="font-editorial text-4xl leading-[40px] my-5">5 Ways Employee Benefits Impact Retention</h1>
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
                        <x-link class="hover:bg-panda-green hover:border-transparent" outlined href="/deals">Read Article</x-link>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</x-layouts.app>
