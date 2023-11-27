<div class="px-[min(6.99vw,50px)] py-8">
    <div class="max-w-[1920px] mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="col-span-2">
                <h2 class="font-light text-3xl">Shopping bag</h2>
                <x-hr />
                @if (cart()->items()->isNotEmpty())
                    <div class="divide-y">
                        <div class="p-4 hidden lg:block">
                            <div class="flex gap-6">
                                <div class="w-full grid grid-cols-1 lg:grid-cols-4 gap-6">
                                    <div>Item</div>
                                    <div>Item Price</div>
                                    <div>Qty</div>
                                    <div>Total Price</div>
                                </div>
                            </div>
                        </div>
                        @foreach (cart()->items() as $id => $item)
                            <div class="p-4">
                                @if ($loop->first)
                                    <div class="py-4"></div>
                                @endif
                                <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                    <div class="flex gap-4 items-center">
                                        {{ $item['itemable']->brand->getFirstMedia('logo')?->img()->attributes(['class' => 'w-20']) }}
                                        <div>
                                            <h5>{{ $item['itemable']->name }}</h5>
                                            <span class="lg:hidden">
                                                {{ Filament\Support\format_money($item['amount'] / 100, 'USD') }}/item
                                            </span>
                                            <div class="lg:hidden">
                                                <span class="line-through">{{ Filament\Support\format_money($item['subtotal'] / 100, 'USD') }}</span>
                                                {{ Filament\Support\format_money($item['item_total'] / 100, 'USD') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hidden lg:block">
                                        {{ Filament\Support\format_money($item['amount'] / 100, 'USD') }}
                                    </div>
                                    <div class="">
                                        @if ($item['itemable']->voucher_type == \App\Enums\DiscountVoucherTypeEnum::DefinedAmountsGiftCard)
                                            <x-input x-on:change="$wire.updateItem('{{ $id }}', $event.target.value, '{{ $item['amount'] }}').then(() => $wire.$refresh())" value="{{ $item['quantity'] }}"
                                                type="number" class="px-2 w-full max-w-[5rem] border !border-solid border-black" min="1" />
                                        @endif
                                        {{-- @if($item['itemable']->voucher_type == \App\Enums\DiscountVoucherTypeEnum::TopUpGiftCard)
                                        <div class="flex items-center space-x-1">
                                            <span>$</span>
                                            <x-input x-on:change="$wire.updateItem('{{ $id }}', '{{ $item['quantity'] }}', $event.target.value).then(() => $wire.$refresh())" value="{{ $item['amount'] / 100 }}"
                                                type="number" class="px-2 w-full max-w-full border !border-solid border-black" :min="$item['itemable']->bh_min" :max="$item['itemable']->bh_max" />
                                            </div>
                                        @endif --}}
                                    </div>
                                    <div class="hidden lg:block">
                                        <span class="line-through">{{ Filament\Support\format_money($item['subtotal'] / 100, 'USD') }}</span>
                                        {{ Filament\Support\format_money($item['item_total'] / 100, 'USD') }}
                                    </div>
                                </div>

                                <div class="flex justify-end gap-6 mt-4">
                                    {{ ($this->removeItem)(['id' => $id]) }}
                                    {{ ($this->saveItem)(['id' => $id]) }}
                                    <div>
                                        <x-discount-terms :record="$item['itemable']" />
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center gap-2">
                        <p>No Items yet</p>
                        <x-link :href="route('deals.index')" outlined class="hover:bg-panda-green">
                            Find Deals
                        </x-link>
                    </div>
                @endif
            </div>
            <div>
                <div class="sticky top-[5rem]">
                    <h2 class="font-light text-3xl">Order Summary</h2>
                    <x-hr />
                    <table class="table w-full">
                        <tbody>
                            <tr>
                                <td>Subtotal</td>
                                <td align="right">{{ Filament\Support\format_money(cart()->subtotal() / 100, 'USD') }}</td>
                            </tr>
                            <tr>
                                <td>Saving</td>
                                <td align="right">{{ Filament\Support\format_money(cart()->discount() / 100, 'USD') }}</td>
                            </tr>
                            <tr>
                                <td>Est. Tax</td>
                                <td align="right">{{ Filament\Support\format_money(cart()->tax() / 100, 'USD') }}</td>
                            </tr>
                            <tr class="font-bold">
                                <td>Total</td>
                                <td align="right">{{ Filament\Support\format_money(cart()->total() / 100, 'USD') }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <x-hr />
                    @if (cart()->count() > 0)
                        <x-button class="hover:bg-panda-green hover:border-transparent" outlined x-data x-on:click="$dispatch('open-modal', {id: 'cardknox'})">
                            Checkout
                        </x-button>
                    @endif
                </div>
            </div>
        </div>
        <div class="my-20"></div>
        @php
            $savedProducts = savedProduct()->get();
        @endphp
        @if ($savedProducts->isNotEmpty())
            <div x-data="{ shown: false }" x-intersect.delay.500ms="shown = true">
                <div x-show="shown" x-transition.opcaity.duration.1000ms>
                    <h3 class="text-4xl">Saved for later</h3>
                    <x-hr />
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        @foreach ($savedProducts as $record)
                            <x-deal-card :$record />
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
    <x-cardknox-form />
    <x-filament-actions::modals />
</div>
