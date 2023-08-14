@props(['old' => false])
@unless ($old)
    <footer class="bg-black text-white">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-8">
            <div class="md:hidden text-white space-y-6">
                <h4>Panda People</h4>
                <input class="block w-full bg-transparent border-b border-white text-white" placeholder="EMAIL" />
                <x-button type="submit" outlined color="white" class="inline-block">
                    Sign Up
                </x-button>
            </div>
            <x-link href="/">
                <x-logo class="text-white" />
            </x-link>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <ul>
                    <li><x-link href="/">Home</x-link></li>
                    <li><x-link href="/benefits">Benefits</x-link></li>
                    <li><x-link href="/deals">Deals</x-link></li>
                    <li><x-link href="/help">Help</x-link></li>
                </ul>
                <ul>
                    <li><x-link href="/">Sign in</x-link></li>
                    <li><x-link href="/benefits">My Account</x-link></li>
                    <li><x-link href="/deals">Contact Us</x-link></li>
                    <li><x-link href="/help">For Employers</x-link></li>
                </ul>
                <ul>
                    <li><x-link href="/">LinkedIn</x-link></li>
                    <li><x-link href="/benefits">Instagram</x-link></li>
                    <li><x-link href="/deals">Facebook</x-link></li>
                    <li><x-link href="/help">Youtube</x-link></li>
                </ul>
            </div>
            <div class="hidden md:block text-white space-y-6">
                <h4>Panda People</h4>
                <input class="block w-full bg-transparent border-b border-white text-white" placeholder="EMAIL" />
                <x-button type="submit" outlined color="white" class="inline-block">
                    Sign Up
                </x-button>
            </div>
        </div>
        <div class="border-t border-white p-8 flex gap-8 items-center">
            <h6 class="">Panda People® {{ date('Y') }} © All Rights Reserved</h6>
            <x-link href="#" class="">Privacy Policy</x-link>
        </div>
    </footer>
@endunless
@if ($old)
    <footer class="w-full flex flex-col justify-between px-[min(6.99vw,50px)] lg:pl-12 h-[75px] lg:h-[100px] items-center mx-auto" style="max-width:1920px">
        <div class="h-[200px]"></div>
        <div class="w-full">
            <x-black-line />
            <div class="w-full my-5">
                <div class="flex justify-start mt-10">
                    <p class="text-[20px] font-aeonik mr-10">Panda People® 2023 © All Rights Reserved</p>
                    <a href="#" class="text-[20px] font-aeonik hover:text-blue-800 ml-10">Privacy Policy</a>
                </div>
            </div>
        </div>
    </footer>
@endif
