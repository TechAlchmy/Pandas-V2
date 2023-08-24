<div>
    <section class="px-[min(6.99vw,50px)] max-w-[1920px] mx-auto py-8">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="flex items-center lg:justify-center">
                @if ($record->brand->hasMedia('logo'))
                    {{ $record->brand->getFirstMedia('logo')->img()->attributes(['class' => 'max-w-[10rem] w-full']) }}
                @else
                    <div class="bg-gray w-full h-8">
                        No Image
                    </div>
                @endif
            </div>
            <div class="space-y-6">
                <h1 class="text-4xl">
                    {{ $record->name }}
                </h1>
                @if ($record->is_amount_single)
                    <div>
                        <span>$</span>
                        <span class="text-3xl font-light">{{ $record->amount[0] }}</span>
                    </div>
                @endif
                <div class="flex gap-6">
                    @if (!$this->record->is_purchased)
                        <div x-data class="space-y-6">
                            <div class="flex gap-6 items-center">
                                @if (!$this->record->is_amount_single)
                                    <select wire:model.live="amount" class="border border-black">
                                        @foreach ($this->record->amount as $amount)
                                            <option value="{{ $amount }}">{{ Filament\Support\format_money($amount, 'USD') }}</option>
                                        @endforeach
                                    </select>
                                @endif
                                <x-input class="lg:max-w-[50%] !border-solid border-black p-2" type="number" wire:model="quantity" min="1" />
                            </div>
                            <div class="flex gap-6 items-center">
                                <x-button wire:click="addToCart" outlined>
                                    Add to cart
                                </x-button>
                                {{ $this->redeemAction }}
                            </div>
                        </div>
                    @endif
                    @if ($record->is_purchased)
                        @if ($record->cta == \App\Enums\DiscountCallToActionEnum::GoToSite)
                            <x-link :href="$record->link" outlined size="lg">
                                Go to link
                            </x-link>
                        @endif
                        @if ($record->cta == \App\Enums\DiscountCallToActionEnum::GetCode)
                            <div x-data="{ modalOpen: false }">
                                <x-button x-on:click="modalOpen = true" outlined size="lg">
                                    Redeem
                                </x-button>
                                <template x-teleport="body">
                                    <div x-show="modalOpen" class="fixed top-0 left-0 z-[99] flex items-center justify-center w-screen h-screen" x-cloak>
                                        <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                            x-transition:leave="ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="modalOpen=false"
                                            class="absolute inset-0 w-full h-full bg-black bg-opacity-40"></div>
                                        <div x-show="modalOpen" x-trap.inert.noscroll="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative w-full py-6 bg-white border border-black px-7 sm:max-w-lg">
                                            <div class="flex items-center justify-between pb-2">
                                                <h3 class="text-4xl font-light">{{ $record->percentage }}% off!</h3>
                                                <button @click="modalOpen=false"
                                                    class="absolute top-0 right-0 flex items-center justify-center w-8 h-8 mt-5 mr-5 text-gray-600 rounded-full hover:text-gray-800 hover:bg-gray-50">
                                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div x-data="{
                                                copyText: `{{ $record->code }}`,
                                                copyNotification: false,
                                                copyToClipboard() {
                                                    navigator.clipboard.writeText(this.copyText);
                                                    this.copyNotification = true;
                                                    let that = this;
                                                    setTimeout(function() {
                                                        that.copyNotification = false;
                                                    }, 3000);
                                                }
                                            }" class="relative w-auto space-y-6">
                                                <p>This is placeholder text. Replace it with your own content.</p>
                                                <div class="p-8 bg-neutral-100 text-center">
                                                    <p class="text-2xl font-light">
                                                        {{ $record->code }}
                                                    </p>
                                                </div>
                                                <div class="text-center">
                                                    <x-button x-on:click="copyToClipboard" x-text="copyNotification ? `Copied!` : `Copy Code`" outlined class="mx-auto">
                                                    </x-button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </section>
    @if ($related->isNotEmpty())
        <section class='px-[min(6.99vw,50px)] py-8 max-w-[1920px] mx-auto'>
            <x-hr />
            <h3 class="text-4xl">Related Deals</h3>
            <div class="h-28"></div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($related as $deal)
                    <x-deal-card :record="$deal" :record-clicks="true" />
                @endforeach
            </div>
        </section>
    @endif
    @if ($popular->isNotEmpty())
        <section class='px-[min(6.99vw,50px)] py-8 max-w-[1920px] mx-auto'>
            <x-hr />
            <h3 class="text-4xl">Related Deals</h3>
            <div class="h-28"></div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($popular as $deal)
                    <x-deal-card :record="$deal" :record-clicks="true" />
                @endforeach
            </div>
        </section>
    @endif
    <div class="checkout-modal">
        <x-filament-actions::modals />
    </div>
    <livewire:resources.recently-viewed-resource.widgets.create-recently-viewed :viewable="$record" />
</div>
