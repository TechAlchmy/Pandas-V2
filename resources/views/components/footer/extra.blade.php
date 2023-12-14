<footer x-data="{ shown: false }" x-intersect.once="shown = true">
    <div class="text-white bg-black">
        <div class="max-w-[1920px] mx-auto">
            <div x-show="shown" x-transition:enter.duration.1000ms.opactiy class="grid grid-cols-1 gap-6 py-16 px-[min(6.99vw,50px)] md:grid-cols-3">
                <div class="flex justify-between order-2 md:order-1 md:col-span-2 md:justify-around">
                    <x-a href="/">
                        <x-logo class="text-white" />
                    </x-a>
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-3">
                        <ul>
                            <li><x-a href="/">Home</x-a></li>
                            <li><x-a href="/benefits">Benefits</x-a></li>
                            <li><x-a href="/deals">Deals</x-a></li>
                            <li><x-a href="/help">Help</x-a></li>
                        </ul>
                        <ul>
                            @guest
                                <li><x-a href="/login">Sign in</x-a></li>
                            @endguest
                            @auth
                                <li><x-a href="/dashboard">My Account</x-a></li>
                            @endauth
                            <li><x-a href="/contact-us">Contact Us</x-a></li>
                            <li><x-a href="/employer">For Employers</x-a></li>
                        </ul>
                        <ul class="">
                            <li><x-a class="text-white" href="/">LinkedIn</x-a></li>
                            <li><x-a class="text-white" href="/">Instagram</x-a></li>
                            <li><x-a class="text-white" href="/">Facebook</x-a></li>
                            <li><x-a class="text-white" href="/">Youtube</x-a></li>
                        </ul>
                        {{-- <ul class="items-center hidden gap-2 md:flex md:flex-col">
                            <li><x-a href="/">@svg('fab-linkedin', 'w-5 h-5')</x-a></li>
                        <li><x-a href="/benefits">@svg('fab-instagram', 'w-5 h-5')</x-a></li>
                        <li><x-a href="/deals">@svg('fab-facebook', 'w-5 h-5')</x-a></li>
                        <li><x-a href="/help">@svg('fab-youtube', 'w-5 h-5')</x-a></li>
                            <li><x-a href="/"><img src="https://upload.wikimedia.org/wikipedia/commons/8/81/LinkedIn_icon.svg" class="w-5 h-5" /></x-a></li>
                            <li><x-a href="/benefits"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/95/Instagram_logo_2022.svg/1920px-Instagram_logo_2022.svg.png" class="w-5 h-5" /></x-a></li>
                            <li><x-a href="/deals"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b9/2023_Facebook_icon.svg/1920px-2023_Facebook_icon.svg.png" class="w-5 h-5" /></x-a></li>
                            <li><x-a href="/help"><img src="https://upload.wikimedia.org/wikipedia/commons/e/ef/Youtube_logo.png?20220706172052" class="w-5 h-5" /></x-a></li>
                        </ul> --}}
                    </div>
                    {{-- <ul class="flex items-center justify-center gap-2 md:hidden">
                        <li><x-a href="/">@svg('fab-linkedin', 'w-5 h-5')</x-a></li>
                        <li><x-a href="/benefits">@svg('fab-instagram', 'w-5 h-5')</x-a></li>
                        <li><x-a href="/deals">@svg('fab-facebook', 'w-5 h-5')</x-a></li>
                        <li><x-a href="/help">@svg('fab-youtube', 'w-5 h-5')</x-a></li>
                        <li><x-a href="/"><img src="https://upload.wikimedia.org/wikipedia/commons/8/81/LinkedIn_icon.svg" class="w-5 h-5" /></x-a></li>
                        <li><x-a href="/benefits"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/95/Instagram_logo_2022.svg/1920px-Instagram_logo_2022.svg.png" class="w-5 h-5" /></x-a></li>
                        <li><x-a href="/deals"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b9/2023_Facebook_icon.svg/1920px-2023_Facebook_icon.svg.png" class="w-5 h-5" /></x-a></li>
                        <li><x-a href="/help"><img src="https://upload.wikimedia.org/wikipedia/commons/e/ef/Youtube_logo.png?20220706172052" class="w-5 h-5" /></x-a></li>
                    </ul> --}}
                </div>
                <div class="order-1 md:order-2  md:col-span-1 pb-16 border-b md:border-b-0 md:pb-0">
                    <livewire:resources.subscriber-resource.forms.create-subscriber-form />
                </div>
            </div>
        </div>
        <div class="flex items-center gap-8 p-8 border-t border-white">
            <div class="max-w-[1920px] mx-auto min-w-full flex flex-wrap gap-6">
                <h6 class="">Panda People® {{ date('Y') }} © All Rights Reserved</h6>
                <x-a href="#" class="">Privacy Policy</x-a>
            </div>
        </div>
    </div>
</footer>
