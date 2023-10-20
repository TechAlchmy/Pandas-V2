<div x-init="setTimeout(() => $wire.updateViews(), 5000)">
    <section class="px-[min(6.99vw,50px)] max-w-[1920px] mx-auto py-8">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="flex items-center lg:justify-center">
                @if ($this->record->brand->hasMedia('logo'))
                    {{ $this->record->brand->getFirstMedia('logo')->img()->attributes(['class' => 'max-w-[10rem] w-full']) }}
                @endif
            </div>
            <div class="space-y-6">
                <div class="space-y-1">
                    <h1 class="text-4xl">
                        {{ $this->record->brand->name }}
                    </h1>
                    <p>{{ $this->record->name }}</p>
                </div>
                @if ($this->record->is_amount_single)
                    <div>
                        <span>$</span>
                        <span class="text-3xl font-light">{{ $this->record->money_amount[0]->getAmount() }}</span>
                    </div>
                @endif
                @if ($this->record->excerpt)
                    <div>{{ $this->record->excerpt }}</div>
                @endif
                <div class="flex gap-6">
                    @if (in_array($this->record->voucher_type, [\App\Enums\DiscountVoucherTypeEnum::DefinedAmountsGiftCard, \App\Enums\DiscountVoucherTypeEnum::TopUpGiftCard]))
                        <div x-data class="space-y-6">
                            <div class="flex gap-6 items-center">
                                @if ($this->record->voucher_type == \App\Enums\DiscountVoucherTypeEnum::TopUpGiftCard)
                                    <div class="w-full">
                                        <div class="flex items-center space-x-1 w-full">
                                            <span>$</span>
                                            <x-input class="w-full !border-solid border-black p-2" type="number" wire:model="amount" :min="$this->record->bh_min / 100" :max="$this->record->bh_max / 100" />
                                        </div>
                                        <div class="flex items-center justify-between w-full text-xs mt-2">
                                            <span>{{ \Filament\Support\format_money($this->record->bh_min / 100, 'USD') }}</span>
                                            <span>{{ \Filament\Support\format_money($this->record->bh_max / 100, 'USD') }}</span>
                                        </div>
                                    </div>
                                @endif
                                @if($this->record->voucher_type == \App\Enums\DiscountVoucherTypeEnum::DefinedAmountsGiftCard)
                                    @if (!$this->record->is_amount_single)
                                        <select wire:model.live.number="amount" class="border border-black">
                                            @foreach ($this->record->amount as $amount)
                                                <option value="{{ $amount }}">{{ Filament\Support\format_money($amount / 100, 'USD') }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                    <x-input class="lg:max-w-[50%] !border-solid border-black p-2" type="number" wire:model="quantity" min="1" />
                                @endif
                            </div>
                            <div class="flex gap-6 items-center">
                                <x-button class="hover:bg-panda-green" x-on:click="$wire.addToCart();$wire.updateClicks()" outlined>
                                    {{ $this->record->cta }}
                                </x-button>
                                <x-button class="hover:bg-panda-green" x-data x-on:click="$dispatch('open-modal', {id: 'cardknox'})" outlined size="lg">
                                    Buy Now
                                </x-button>
                            </div>
                        </div>
                    @endif
                    @if ($this->record->voucher_type == \App\Enums\DiscountVoucherTypeEnum::ExternalLink)
                        <x-link x-on:click="$wire.handleClick()" class="hover:bg-panda-green" :href="$this->record->link" outlined size="lg">
                            {{ $this->record->cta }}
                        </x-link>
                    @endif
                    @if ($this->record->voucher_type == \App\Enums\DiscountVoucherTypeEnum::FixedDiscountCode)
                        <div x-data="{ modalOpen: false }">
                            <x-button class="hover:bg-panda-green" x-on:click="modalOpen = true" outlined size="lg">
                                {{ $this->record->cta }}
                            </x-button>
                            <template x-teleport="body">
                                <div x-show="modalOpen" class="fixed top-0 left-0 z-[99] flex items-center justify-center w-screen h-screen" x-cloak>
                                    <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-300"
                                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="modalOpen=false" class="absolute inset-0 w-full h-full bg-black bg-opacity-40"></div>
                                    <div x-show="modalOpen" x-trap.inert.noscroll="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative w-full py-6 bg-white border border-black px-7 sm:max-w-lg">
                                        <div class="flex items-center justify-between pb-2">
                                            <h3 class="text-4xl font-light">{{ $this->record->percentage }}% off!</h3>
                                            <button @click="modalOpen=false"
                                                class="absolute top-0 right-0 flex items-center justify-center w-8 h-8 mt-5 mr-5 text-gray-600 rounded-full hover:text-gray-800 hover:bg-gray-50 hover:bg-panda-green">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div x-data="{
                                            copyText: `{{ $this->record->code }}`,
                                            copyNotification: false,
                                            copyToClipboard() {
                                                navigator.clipboard.writeText(this.copyText);
                                                this.copyNotification = true;
                                                let that = this;
                                                setTimeout(function() {
                                                    that.copyNotification = false;
                                                }, 3000);
                                                $wire.updateClicks()
                                            }
                                        }" class="relative w-auto space-y-6">
                                            <p>This is placeholder text. Replace it with your own content.</p>
                                            <div class="p-8 bg-neutral-100 text-center">
                                                <p class="text-2xl font-light">
                                                    {{ $this->record->code }}
                                                </p>
                                            </div>
                                            <div class="text-center">
                                                <x-button class="hover:bg-panda-green" x-on:click="copyToClipboard" x-text="copyNotification ? `Copied!` : `Copy Code`" outlined class="mx-auto">
                                                </x-button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <x-deals-section title="Related Deals" :records="$related" />
    <x-deals-section title="Popular Deals" :records="$popular" />
    <livewire:resources.recently-viewed-resource.widgets.create-recently-viewed :viewable="$this->record" />
    <x-cardknox-form />
</div>
