<footer x-data="{ shown: false }" x-intersect.once="shown = true">
    <div class="text-white bg-black">
        <div class="max-w-[1920px] mx-auto">
            <div x-show="shown" x-transition:enter.duration.1000ms.opactiy class="grid grid-cols-1 gap-6 p-8 md:grid-cols-3">
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
                    <ul class="space-y-2">
                        <li><x-a href="/">@svg('fab-linkedin', 'w-5 h-5')</x-a></li>
                        <li><x-a href="/benefits">@svg('fab-instagram', 'w-5 h-5')</x-a></li>
                        <li><x-a href="/deals">@svg('fab-facebook', 'w-5 h-5')</x-a></li>
                        <li><x-a href="/help">@svg('fab-youtube', 'w-5 h-5')</x-a></li>
                    </ul>
                </div>
                <livewire:resources.subscriber-resource.forms.create-subscriber-form />
            </div>
        </div>
        <div class="flex items-center gap-8 p-8 border-t border-white">
            <div class="max-w-[1920px] mx-auto">
                <h6 class="">Panda People® {{ date('Y') }} © All Rights Reserved</h6>
                <x-a href="#" class="">Privacy Policy</x-a>
            </div>
        </div>
    </div>
</footer>
