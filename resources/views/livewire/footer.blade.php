<footer class="bg-black text-white">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-8">
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
        <livewire:resources.subscriber-resource.forms.create-subscriber-form />
    </div>
    <div class="border-t border-white p-8 flex gap-8 items-center">
        <h6 class="">Panda People® {{ date('Y') }} © All Rights Reserved</h6>
        <x-link href="#" class="">Privacy Policy</x-link>
    </div>
</footer>
