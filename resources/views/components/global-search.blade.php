<div class="flex items-center">
    <div class="relative">
        <div x-id="['input']" class="fi-global-search-field">
            <label x-bind:for="$id('input')" class="sr-only" for="input-8">
                Global search
            </label>
            <div class="flex shadow-sm transition duration-75 bg-white border-b border-black">
                <div class="min-w-0 flex-1">
                    <form action="search">
                        <x-input placeholder="Search Deals" type="search" name="q" />
                    </form>
                </div>
                <div class="flex items-center gap-x-3 ps-3 pe-2">
                    @svg('magnifying-glass', 'w-6 h-6')
                    {{-- <svg wire:loading.remove.delay="1" wire:target="search" class="fi-input-wrapper-icon h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd"></path>
                    </svg> --}}
                    {{-- <svg fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="animate-spin fi-input-wrapper-icon h-5 w-5 text-gray-400 dark:text-gray-500" wire:loading.delay="wire:loading.delay"
                        wire:target="search">
                        <path clip-rule="evenodd"
                            d="M12 19C15.866 19 19 15.866 19 12C19 8.13401 15.866 5 12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19ZM12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                            fill-rule="evenodd" fill="currentColor" opacity="0.2"></path>
                        <path d="M2 12C2 6.47715 6.47715 2 12 2V5C8.13401 5 5 8.13401 5 12H2Z" fill="currentColor"></path>
                    </svg> --}}
                </div>
            </div>
        </div>
        {{--
        <div x-data="{
            isOpen: false,

            open: function(event) {
                this.isOpen = true
            },

            close: function(event) {
                this.isOpen = false
            },
        }" x-init="$nextTick(() => open())" x-on:click.away="close()" x-on:keydown.escape.window="close()" x-on:open-global-search-results.window="$nextTick(() => open())" x-show="isOpen"
            x-transition:enter-start="opacity-0" x-transition:leave-end="opacity-0"
            class="fi-global-search-results-ctn absolute end-0 z-10 mt-2 max-h-96 w-screen max-w-sm overflow-auto rounded-lg bg-white shadow-lg ring-1 ring-gray-950/5 transition dark:bg-gray-900 dark:ring-white/10"
            data-has-alpine-state="true">
        </div> --}}
    </div>
</div>
