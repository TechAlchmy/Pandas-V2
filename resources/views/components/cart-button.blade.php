<div class="relative" x-data="{ popoverOpen: false }">
    <x-a href="/checkout" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
        @svg('shopping-bag', 'w-6')
    </x-a>
    <div x-ref="panel" x-cloak x-show="popoverOpen" x-on:click.away="popoverOpen = false" class="hidden lg:block absolute top-12 right-0 z-10 bg-white p-6 min-w-[24rem] border border-black">
        <div class="space-y-4">
            <div class="flex justify-between gap-6">
                <h3 class="text-xl font-light">New Item has been added</h3>
                <button x-on:click="popoverOpen = false">
                    X
                </button>
            </div>
            <div>
                <x-link href="/checkout" outlined>
                    Checkout
                </x-link>
            </div>
        </div>
    </div>
</div>
