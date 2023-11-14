@props(['record'])
<div x-data="{ termsOpen: false }">
    <button {{ $attributes->merge(['class' => 'hover:underline']) }} x-on:click="termsOpen = true" outlined size="lg">
        Terms and conditions
    </button>
    <template x-teleport="body">
        <div x-show="termsOpen" class="fixed top-0 left-0 z-[99] flex items-center justify-center w-screen h-screen" x-cloak>
            <div x-show="termsOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="termsOpen=false" class="absolute inset-0 w-full h-full bg-black bg-opacity-40"></div>
            <div x-show="termsOpen" x-trap.inert.noscroll="termsOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative w-full py-6 bg-white border border-black px-7 sm:max-w-lg">
                <div class="flex items-center justify-between pb-2">
                    <p class="">{{ $record->terms }}</p>
                </div>
            </div>
        </div>
    </template>
</div>
