<footer class="bg-black text-white">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-8">
        <x-a href="/">
            <x-logo class="text-white" />
        </x-a>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <ul>
                <li><x-a href="/">Home</x-a></li>
                <li><x-a href="/benefits">Benefits</x-a></li>
                <li><x-a href="/deals">Deals</x-a></li>
                <li><x-a href="/help">Help</x-a></li>
            </ul>
            <ul>
                <li><x-a href="/">Sign in</x-a></li>
                <li><x-a href="/benefits">My Account</x-a></li>
                <li><x-a href="/deals">Contact Us</x-a></li>
                <li><x-a href="/help">For Employers</x-a></li>
            </ul>
            <ul>
                <li><x-a href="/">LinkedIn</x-a></li>
                <li><x-a href="/benefits">Instagram</x-a></li>
                <li><x-a href="/deals">Facebook</x-a></li>
                <li><x-a href="/help">Youtube</x-a></li>
            </ul>
        </div>
        <livewire:resources.subscriber-resource.forms.create-subscriber-form />
    </div>
    <div class="border-t border-white p-8 flex gap-8 items-center">
        <h6 class="">Panda People® {{ date('Y') }} © All Rights Reserved</h6>
        <x-a href="#" class="">Privacy Policy</x-a>
    </div>
</footer>
